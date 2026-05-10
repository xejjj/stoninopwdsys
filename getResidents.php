<?php
session_start();
require_once("db.php");

// ── Pagination ────────────────────────────────────────
$per_page    = 10;
$current_page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset      = ($current_page - 1) * $per_page;

// ── Filters ───────────────────────────────────────────
$search       = isset($_GET["search"])   ? trim($_GET["search"])   : "";
$filter_cat   = isset($_GET["category"]) ? trim($_GET["category"]) : "";
$filter_sex   = isset($_GET["sex"])      ? trim($_GET["sex"])      : "";
$filter_status= isset($_GET["status"])   ? trim($_GET["status"])   : "";
$filter_disab = isset($_GET["disability"])? trim($_GET["disability"]): "";

// ── Build WHERE clause ────────────────────────────────
$conditions = [];
$params     = [];
$types      = "";

if (!empty($search)) {
    $conditions[] = "(first_name LIKE ? OR last_name LIKE ? OR middle_name LIKE ?)";
    $s = "%" . $search . "%";
    $params[] = $s; $params[] = $s; $params[] = $s;
    $types   .= "sss";
}
if (!empty($filter_cat)) {
    $conditions[] = "resident_type = ?";
    $params[] = $filter_cat;
    $types   .= "s";
}
if (!empty($filter_sex)) {
    $conditions[] = "sex = ?";
    $params[] = strtolower($filter_sex);
    $types   .= "s";
}
if (!empty($filter_status)) {
    $conditions[] = "status = ?";
    $params[] = $filter_status;
    $types   .= "s";
}
if (!empty($filter_disab)) {
    $conditions[] = "disablity_type LIKE ?";
    $params[] = "%" . $filter_disab . "%";
    $types   .= "s";
}

$where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

// ── Total count (for pagination) ──────────────────────
$count_sql  = "SELECT COUNT(*) as total FROM residents $where";
$count_stmt = mysqli_prepare($conn, $count_sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_rows   = mysqli_fetch_assoc($count_result)["total"];
$total_pages  = max(1, ceil($total_rows / $per_page));

// ── Fetch rows ────────────────────────────────────────
$data_sql  = "SELECT * FROM residents $where ORDER BY last_name ASC LIMIT ? OFFSET ?";
$data_params = array_merge($params, [$per_page, $offset]);
$data_types  = $types . "ii";

$data_stmt = mysqli_prepare($conn, $data_sql);
mysqli_stmt_bind_param($data_stmt, $data_types, ...$data_params);
mysqli_stmt_execute($data_stmt);
$residents_result = mysqli_stmt_get_result($data_stmt);

// ── Badge class helper ────────────────────────────────
function badgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"        => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
    ];
    return $map[strtolower(trim($type))] ?? "badge-physical";
}

// ── Keep filters in pagination links ─────────────────
function buildQuery($page, $extra = []) {
    $params = array_merge($_GET, ["page" => $page], $extra);
    return "?" . http_build_query($params);
}
?>