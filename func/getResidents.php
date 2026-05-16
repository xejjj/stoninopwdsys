<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

/* =========================
   PAGINATION
========================= */

$per_page = 10;

$current_page =
    isset($_GET["page"]) &&
    is_numeric($_GET["page"])
    ? (int)$_GET["page"]
    : 1;

$offset = ($current_page - 1) * $per_page;

/* =========================
   SESSION FILTERS (PERSISTENCE)
========================= */

if (isset($_GET['clear_filters'])) {
    unset($_SESSION['res_filters']);
} 
elseif (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['sex']) || isset($_GET['status']) || isset($_GET['disability'])) {
    $_SESSION['res_filters'] = [
        'search' => $_GET['search'] ?? '',
        'category' => $_GET['category'] ?? '',
        'sex' => $_GET['sex'] ?? '',
        'status' => $_GET['status'] ?? '',
        'disability' => $_GET['disability'] ?? ''
    ];
}

/* =========================
   FILTERS
========================= */

$search = trim($_GET["search"] ?? $_SESSION['res_filters']['search'] ?? "");
$filter_cat = trim($_GET["category"] ?? $_SESSION['res_filters']['category'] ?? "");
$filter_sex = trim($_GET["sex"] ?? $_SESSION['res_filters']['sex'] ?? "");
$filter_status = trim($_GET["status"] ?? $_SESSION['res_filters']['status'] ?? "");
$filter_disab = trim($_GET["disability"] ?? $_SESSION['res_filters']['disability'] ?? "");

/* =========================
   BASE QUERY
========================= */

$base_query = "
FROM residents
LEFT JOIN resident_contacts ON residents.ID = resident_contacts.resident_id
LEFT JOIN resident_disabilities ON residents.ID = resident_disabilities.resident_id
LEFT JOIN resident_emergency_contacts ON residents.ID = resident_emergency_contacts.resident_id
LEFT JOIN resident_family_members ON residents.ID = resident_family_members.resident_id
";

/* =========================
   WHERE CONDITIONS
========================= */

$where = [];

if (!empty($search)) {
    $safe = mysqli_real_escape_string($conn, $search);
    $where[] = "(residents.first_name LIKE '%$safe%' OR residents.middle_name LIKE '%$safe%' OR residents.last_name LIKE '%$safe%')";
}

if (!empty($filter_cat)) {
    $safe = mysqli_real_escape_string($conn, $filter_cat);
    $where[] = "residents.resident_type = '$safe'";
}

if (!empty($filter_sex)) {
    $safe = mysqli_real_escape_string($conn, strtolower($filter_sex));
    $where[] = "residents.sex = '$safe'";
}

if (!empty($filter_disab)) {
    $safe = mysqli_real_escape_string($conn, $filter_disab);
    $where[] = "resident_disabilities.disability_type = '$safe'";
}

if (!empty($filter_status)) {
    if ($filter_status === "Active") $where[] = "residents.record_status = 'active'";
    elseif ($filter_status === "Expired") $where[] = "residents.record_status = 'expired'";
    elseif ($filter_status === "Rejected") $where[] = "residents.application_status = 'rejected'";
    elseif ($filter_status === "Under Review") $where[] = "residents.application_status = 'under review'";
    elseif ($filter_status === "Needs Correction") $where[] = "residents.application_status = 'needs correction'";
}

$where[] = "residents.record_status != 'archived'";

$where_sql = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

/* =========================
   COUNT QUERY
========================= */

$count_sql = "SELECT COUNT(DISTINCT residents.ID) AS total $base_query $where_sql";
$count_result = mysqli_query($conn, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)["total"];
$total_pages = max(1, ceil($total_rows / $per_page));

/* =========================
   DATA QUERY
========================= */

$data_sql = "
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

$base_query
$where_sql
GROUP BY residents.ID

ORDER BY
CASE
    WHEN residents.record_status = 'expired' THEN 0
    WHEN residents.idexpiration_date IS NOT NULL AND DATE(residents.idexpiration_date) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH) THEN 1
    ELSE 2
END,
residents.idexpiration_date ASC,
residents.last_name ASC

LIMIT $per_page OFFSET $offset
";

$residents_result = mysqli_query($conn, $data_sql);

/* =========================
   BADGE CLASS
========================= */

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

/* =========================
   PAGINATION QUERY
========================= */

function buildQuery($page, $extra = []) {
    $params = array_merge($_GET, ["page" => $page], $extra);
    return "?" . http_build_query($params);
}
?>