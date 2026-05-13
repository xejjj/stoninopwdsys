<?php
session_start();
require_once("db.php");

/* TOTAL = ONLY ACTIVE RESIDENTS */
$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM residents 
    WHERE status = 'Active'
");
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row["total"] ?? 0;

/* AGE BRACKETS = ONLY ACTIVE RESIDENTS */
$minors_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents 
    WHERE status = 'Active' AND age BETWEEN 0 AND 17
");
$minors_count = mysqli_fetch_assoc($minors_result)["cnt"] ?? 0;

$adults_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents 
    WHERE status = 'Active' AND age BETWEEN 18 AND 59
");
$adults_count = mysqli_fetch_assoc($adults_result)["cnt"] ?? 0;

$seniors_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents 
    WHERE status = 'Active' AND age >= 60
");
$seniors_count = mysqli_fetch_assoc($seniors_result)["cnt"] ?? 0;

$minors_pct  = $total > 0 ? max(4, round(($minors_count / $total) * 100)) : 0;
$adults_pct  = $total > 0 ? max(4, round(($adults_count / $total) * 100)) : 0;
$seniors_pct = $total > 0 ? max(4, round(($seniors_count / $total) * 100)) : 0;

/* STATUS COUNTS */
$active_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents WHERE status = 'Active'
"))["cnt"] ?? 0;

$under_review_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents WHERE status = 'Under Review'
"))["cnt"] ?? 0;

$needs_correction_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents WHERE status = 'Needs Correction'
"))["cnt"] ?? 0;

$expired_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt FROM residents WHERE status = 'Expired'
"))["cnt"] ?? 0;

/* PIE CHART USES ALL NON-REJECTED RESIDENTS */
$status_total = $active_count + $under_review_count + $needs_correction_count + $expired_count;

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

$active_deg = $status_total > 0 ? ($active_count / $status_total) * 360 : 0;
$under_review_deg = $status_total > 0 ? ($under_review_count / $status_total) * 360 : 0;
$needs_correction_deg = $status_total > 0 ? ($needs_correction_count / $status_total) * 360 : 0;
$expired_deg = $status_total > 0 ? ($expired_count / $status_total) * 360 : 0;

$active_path = $status_total > 0 ? pieSlice(90, 90, 80, 0, $active_deg) : "";

$under_review_path = $status_total > 0
    ? pieSlice(90, 90, 80, $active_deg, $active_deg + $under_review_deg)
    : "";

$needs_correction_path = $status_total > 0
    ? pieSlice(90, 90, 80, $active_deg + $under_review_deg, $active_deg + $under_review_deg + $needs_correction_deg)
    : "";

$expired_path = $status_total > 0
    ? pieSlice(90, 90, 80, $active_deg + $under_review_deg + $needs_correction_deg, 360)
    : "";

/* DIRECTORY ROWS */
$recent_ids = $_SESSION['recent_views'] ?? [];

$status_order = "'Under Review', 'Needs Correction', 'Expired', 'Active'";

if (!empty($recent_ids)) {
    $reversed_recents = array_reverse($recent_ids);
    $id_list = implode(',', array_map('intval', $reversed_recents));

    $query = "SELECT * FROM residents 
              ORDER BY 
                FIELD(status, $status_order) ASC,
                FIELD(ID, $id_list) DESC,
                ID DESC";
} else {
    $query = "SELECT * FROM residents 
              ORDER BY 
                FIELD(status, $status_order) ASC,
                ID DESC";
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

    $key = strtolower(trim(explode(",", $type)[0]));
    return $map[$key] ?? "badge-physical";
}
?>