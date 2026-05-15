<?php
require_once("db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

$search       = trim($_GET["search"] ?? "");
$filter_disab = trim($_GET["disability"] ?? "");
$filter_cat   = trim($_GET["category"] ?? "");
$filter_sex   = trim($_GET["sex"] ?? "");

$per_page = 10;
$current_page = max(1, intval($_GET["page"] ?? 1));
$offset = ($current_page - 1) * $per_page;

function badgeClass(string $type): string {

    return match(strtolower(trim($type))) {

        'cognitive'    => 'badge-cognitive',
        'visual'       => 'badge-visual',
        'physical'     => 'badge-physical',
        'auditory'     => 'badge-auditory',
        'speech'       => 'badge-speech',
        'psychosocial' => 'badge-psycho',

        default        => 'badge-cognitive',
    };
}

$where = [];

$where[] =
    "residents.record_status = 'archived'";

$params = [];
$types  = "";

if (!empty($search)) {

    $where[] =
    "(
        CONCAT(first_name,' ',middle_name,' ',last_name) LIKE ?
        OR
        CONCAT(last_name,', ',first_name,' ',middle_name) LIKE ?
    )";

    $like = "%$search%";

    $params[] = &$like;
    $params[] = &$like;

    $types .= "ss";
}

if (!empty($filter_disab)) {

    $where[] =
        "resident_disabilities.disability_type = ?";

    $params[] = &$filter_disab;

    $types .= "s";
}

if (!empty($filter_cat)) {

    $where[] =
        "resident_type = ?";

    $params[] = &$filter_cat;

    $types .= "s";
}

if (!empty($filter_sex)) {

    $where[] =
        "sex = ?";

    $params[] = &$filter_sex;

    $types .= "s";
}

$where_sql = implode(" AND ", $where);

/* COUNT */

$count_sql = "
SELECT COUNT(DISTINCT residents.ID)

FROM residents

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id

WHERE $where_sql
";

$count_stmt =
    mysqli_prepare($conn, $count_sql);

if (!empty($params)) {
    mysqli_stmt_bind_param(
        $count_stmt,
        $types,
        ...$params
    );
}

mysqli_stmt_execute($count_stmt);

mysqli_stmt_bind_result(
    $count_stmt,
    $total_rows
);

mysqli_stmt_fetch($count_stmt);

mysqli_stmt_close($count_stmt);

$total_pages =
    max(1, ceil($total_rows / $per_page));

$current_page =
    min($current_page, $total_pages);

$offset =
    ($current_page - 1) * $per_page;

/* FETCH */

$data_sql = "
SELECT
    residents.*,

    GROUP_CONCAT(
        DISTINCT resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type

FROM residents

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id

WHERE $where_sql

GROUP BY residents.ID

ORDER BY residents.ID DESC

LIMIT ? OFFSET ?
";

$data_stmt =
    mysqli_prepare($conn, $data_sql);

if (!empty($params)) {

    $all_params = $params;

    $all_params[] = &$per_page;
    $all_params[] = &$offset;

    $all_types = $types . "ii";

    mysqli_stmt_bind_param(
        $data_stmt,
        $all_types,
        ...$all_params
    );

} else {

    mysqli_stmt_bind_param(
        $data_stmt,
        "ii",
        $per_page,
        $offset
    );
}

mysqli_stmt_execute($data_stmt);

$archived_result =
    mysqli_stmt_get_result($data_stmt);

function buildQuery(int $page): string {

    $p = $_GET;

    $p["page"] = $page;

    return "archive.php?" .
        http_build_query($p);
}
?>