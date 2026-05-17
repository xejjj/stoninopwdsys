<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once("db.php");

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM residents WHERE record_status = 'active'");
$total = mysqli_fetch_assoc($total_result)["total"] ?? 0;

$minors_result = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE record_status = 'active' AND TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 0 AND 17");
$minors_count = mysqli_fetch_assoc($minors_result)["cnt"] ?? 0;

$adults_result = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE record_status = 'active' AND TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 59");
$adults_count = mysqli_fetch_assoc($adults_result)["cnt"] ?? 0;

$seniors_result = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE record_status = 'active' AND TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60");
$seniors_count = mysqli_fetch_assoc($seniors_result)["cnt"] ?? 0;

$minors_pct  = $total > 0 ? max(4, round(($minors_count / $total) * 100)) : 0;
$adults_pct  = $total > 0 ? max(4, round(($adults_count / $total) * 100)) : 0;
$seniors_pct = $total > 0 ? max(4, round(($seniors_count / $total) * 100)) : 0;

$active_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE record_status = 'active'"))["cnt"] ?? 0;
$under_review_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE application_status = 'under review'"))["cnt"] ?? 0;
$needs_correction_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE application_status = 'needs correction'"))["cnt"] ?? 0;
$expired_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM residents WHERE record_status = 'expired'"))["cnt"] ?? 0;

$status_total = $active_count + $under_review_count + $needs_correction_count + $expired_count;

if (!function_exists('pieSlice')) {
    function pieSlice($cx, $cy, $r, $startDeg, $endDeg) {
        $start = deg2rad($startDeg - 90);
        $end   = deg2rad($endDeg - 90);
        $large = ($endDeg - $startDeg) > 180 ? 1 : 0;
        $x1 = round($cx + $r * cos($start), 2);
        $y1 = round($cy + $r * sin($start), 2);
        $x2 = round($cx + $r * cos($end), 2);
        $y2 = round($cy + $r * sin($end), 2);
        return "M{$cx},{$cy} L{$x1},{$y1} A{$r},{$r} 0 {$large},1 {$x2},{$y2} Z";
    }
}

$active_deg = $status_total > 0 ? ($active_count / $status_total) * 360 : 0;
$under_review_deg = $status_total > 0 ? ($under_review_count / $status_total) * 360 : 0;
$needs_correction_deg = $status_total > 0 ? ($needs_correction_count / $status_total) * 360 : 0;
$expired_deg = $status_total > 0 ? ($expired_count / $status_total) * 360 : 0;

$active_path = $status_total > 0 ? pieSlice(90,90,80,0,$active_deg) : "";
$under_review_path = $status_total > 0 ? pieSlice(90, 90, 80, $active_deg, $active_deg + $under_review_deg) : "";
$needs_correction_path = $status_total > 0 ? pieSlice(90, 90, 80, $active_deg + $under_review_deg, $active_deg + $under_review_deg + $needs_correction_deg) : "";
$expired_path = $status_total > 0 ? pieSlice(90, 90, 80, $active_deg + $under_review_deg + $needs_correction_deg, 360) : "";

$search = trim($_GET["search"] ?? "");
$dash_filter_disab = trim($_GET["disability"] ?? "");
$dash_filter_cat = trim($_GET["category"] ?? "");
$dash_filter_sex = trim($_GET["sex"] ?? "");
$dash_filter_status = trim($_GET["status"] ?? "");

$is_filtered = !empty($search) || !empty($dash_filter_disab) || !empty($dash_filter_cat) || !empty($dash_filter_sex) || !empty($dash_filter_status);

$query = "
SELECT
    residents.*,
    MAX(resident_contacts.contact_num) AS contact_num,
    MAX(resident_contacts.socials) AS socials,
    MAX(resident_emergency_contacts.name) AS emergency_name,
    MAX(resident_emergency_contacts.contact_num) AS emergency_number,
    MAX(resident_emergency_contacts.relationship) AS emergency_relation,
    GROUP_CONCAT(DISTINCT resident_disabilities.disability_type SEPARATOR ', ') AS disability_type,
    MAX(resident_disabilities.notes) AS disability_remarks,
    MAX(CASE WHEN resident_family_members.relationship = 'Father' THEN resident_family_members.name END) AS father_name,
    MAX(CASE WHEN resident_family_members.relationship = 'Mother' THEN resident_family_members.name END) AS mother_name,
    MAX(CASE WHEN resident_family_members.relationship = 'Spouse' THEN resident_family_members.name END) AS spouse_name,
    MAX(CASE WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse') THEN resident_family_members.name END) AS guardian_name,
    MAX(CASE WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse') THEN resident_family_members.relationship END) AS guardian_rel,
    MAX(CASE WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse') THEN resident_family_members.contact_num END) AS guardian_number
FROM residents
LEFT JOIN resident_contacts ON residents.ID = resident_contacts.resident_id
LEFT JOIN resident_disabilities ON residents.ID = resident_disabilities.resident_id
LEFT JOIN resident_emergency_contacts ON residents.ID = resident_emergency_contacts.resident_id
LEFT JOIN resident_family_members ON residents.ID = resident_family_members.resident_id
";

$where = [];

if ($is_filtered) {
    if (!empty($search)) {
        $safe = mysqli_real_escape_string($conn, $search);
        $where[] = "(residents.first_name LIKE '%$safe%' OR residents.middle_name LIKE '%$safe%' OR residents.last_name LIKE '%$safe%')";
    }
    if (!empty($dash_filter_cat)) {
        $where[] = "residents.resident_type = '" . mysqli_real_escape_string($conn, $dash_filter_cat) . "'";
    }
    if (!empty($dash_filter_sex)) {
        $where[] = "residents.sex = '" . mysqli_real_escape_string($conn, $dash_filter_sex) . "'";
    }
    if (!empty($dash_filter_status)) {
        if ($dash_filter_status === "Active") {
            $where[] = "residents.record_status = 'active'";
        } elseif ($dash_filter_status === "Expired") {
            $where[] = "residents.record_status = 'expired'";
        } elseif ($dash_filter_status === "Under Review") {
            $where[] = "residents.application_status = 'under review'";
        } elseif ($dash_filter_status === "Needs Correction") {
            $where[] = "residents.application_status = 'needs correction'";
        }
    }
    if (!empty($dash_filter_disab)) {
        $where[] = "resident_disabilities.disability_type = '" . mysqli_real_escape_string($conn, $dash_filter_disab) . "'";
    }
} else {
    // THIS IS THE CRITICAL CHANGE: Tell SQL to look for the IDs
    if (!empty($_SESSION['recent_views'])) {
        $clean_ids = array_map('intval', $_SESSION['recent_views']);
        $id_list = implode(',', $clean_ids);
        $where[] = "residents.ID IN ($id_list)";
        
        // Use FIELD() to retain the exact ordering of the session array
        $order_by_clause = "ORDER BY FIELD(residents.ID, $id_list)";
    } else {
        $where[] = "1=0"; // Returns empty table if no recent views exist
    }
}

$where[] = "residents.record_status != 'archived'";
$query .= " WHERE " . implode(" AND ", $where);

$query .= " GROUP BY residents.ID ";

// Apply the custom ordering ONLY if pulling recent views, otherwise apply standard sorts
if (!$is_filtered && !empty($_SESSION['recent_views'])) {
    $query .= $order_by_clause;
} else {
    $query .= "
    ORDER BY
    CASE
        WHEN residents.record_status = 'expired' THEN 0
        WHEN residents.application_status = 'needs correction' THEN 1
        WHEN residents.application_status = 'under review' THEN 2
        WHEN residents.record_status = 'active' THEN 3
        ELSE 4
    END,
    residents.ID DESC
    ";
}

$residents_result = mysqli_query($conn, $query);

function badgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"     => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
        "others"       => "badge-others",
    ];
    return $map[strtolower(trim($type))] ?? "badge-physical";
}
?>