<?php
session_start();
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if (($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../dashboard.php");
    exit();
}

$per_page = 10;
$current_page = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($current_page - 1) * $per_page;

$search = trim($_GET["search"] ?? "");
$action_filter = trim($_GET["action"] ?? "");

$conditions = [];
$params = [];
$types = "";

if ($search !== "") {
    $conditions[] = "(admin_name LIKE ? OR module LIKE ? OR description LIKE ?)";
    $s = "%$search%";
    $params[] = $s;
    $params[] = $s;
    $params[] = $s;
    $types .= "sss";
}

if ($action_filter !== "") {
    $conditions[] = "action = ?";
    $params[] = $action_filter;
    $types .= "s";
}

$where = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

$count_sql = "SELECT COUNT(*) AS total FROM audit_logs $where";
$count_stmt = mysqli_prepare($conn, $count_sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}

mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_rows = mysqli_fetch_assoc($count_result)["total"];
$total_pages = max(1, ceil($total_rows / $per_page));

$data_sql = "SELECT * FROM audit_logs $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$data_params = array_merge($params, [$per_page, $offset]);
$data_types = $types . "ii";

$data_stmt = mysqli_prepare($conn, $data_sql);
mysqli_stmt_bind_param($data_stmt, $data_types, ...$data_params);
mysqli_stmt_execute($data_stmt);
$audit_result = mysqli_stmt_get_result($data_stmt);

function buildQuery($page, $extra = []) {
    $params = array_merge($_GET, ["page" => $page], $extra);
    return "?" . http_build_query($params);
}

function actionBadge($action) {
    $action = strtoupper($action);

    return match ($action) {

    "CREATE"  => "badge-create",
    "UPDATE"  => "badge-update",

    "APPROVE" => "badge-restore",
    "REJECT"  => "badge-delete",

    "ARCHIVE" => "badge-archive",
    "RESTORE" => "badge-restore",
    "DELETE"  => "badge-delete",

    "LOGIN"   => "badge-login",

    default   => "badge-default",
};
}
?>