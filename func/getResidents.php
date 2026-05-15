<?php
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
   FILTERS
========================= */

$search =
    trim($_GET["search"] ?? "");

$filter_cat =
    trim($_GET["category"] ?? "");

$filter_sex =
    trim($_GET["sex"] ?? "");

$filter_status =
    trim($_GET["status"] ?? "");

$filter_disab =
    trim($_GET["disability"] ?? "");

/* =========================
   BASE QUERY
========================= */

$base_query = "
FROM residents

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id
";

/* =========================
   WHERE CONDITIONS
========================= */

$where = [];

if (!empty($search)) {

    $safe =
        mysqli_real_escape_string(
            $conn,
            $search
        );

    $where[] = "
    (
        first_name LIKE '%$safe%'
        OR middle_name LIKE '%$safe%'
        OR last_name LIKE '%$safe%'
    )";
}

if (!empty($filter_cat)) {

    $safe =
        mysqli_real_escape_string(
            $conn,
            $filter_cat
        );

    $where[] =
        "resident_type = '$safe'";
}

if (!empty($filter_sex)) {

    $safe =
        mysqli_real_escape_string(
            $conn,
            strtolower($filter_sex)
        );

    $where[] =
        "sex = '$safe'";
}

if (!empty($filter_disab)) {

    $safe =
        mysqli_real_escape_string(
            $conn,
            $filter_disab
        );

    $where[] = "
    resident_disabilities.disability_type
    = '$safe'
    ";
}

/* STATUS FILTER */

if (!empty($filter_status)) {

    if ($filter_status === "Active") {

        $where[] =
            "record_status = 'active'";

    } elseif ($filter_status === "Expired") {

        $where[] =
            "record_status = 'expired'";

    } elseif ($filter_status === "Rejected") {

        $where[] =
            "application_status = 'rejected'";

    } elseif ($filter_status === "Under Review") {

        $where[] =
            "application_status = 'under review'";

    } elseif ($filter_status === "Needs Correction") {

        $where[] =
            "application_status = 'needs correction'";
    }
}

/* HIDE ARCHIVED */

$where[] =
    "record_status != 'archived'";

$where_sql =
    count($where) > 0
    ? "WHERE " . implode(" AND ", $where)
    : "";

/* =========================
   COUNT QUERY
========================= */

$count_sql = "
SELECT COUNT(DISTINCT residents.ID)
AS total

$base_query
$where_sql
";

$count_result =
    mysqli_query($conn, $count_sql);

$total_rows =
    mysqli_fetch_assoc($count_result)["total"];

$total_pages =
    max(1, ceil($total_rows / $per_page));

/* =========================
   DATA QUERY
========================= */

$data_sql = "
SELECT
    residents.*,

    GROUP_CONCAT(
        resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type

$base_query

$where_sql

GROUP BY residents.ID

ORDER BY

CASE
    WHEN record_status = 'expired'
    THEN 0
    ELSE 1
END,

last_name ASC

LIMIT $per_page OFFSET $offset
";

$residents_result =
    mysqli_query($conn, $data_sql);

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

    return $map[
        strtolower(trim($type))
    ] ?? "badge-physical";
}

/* =========================
   PAGINATION QUERY
========================= */

function buildQuery($page, $extra = []) {

    $params =
        array_merge(
            $_GET,
            ["page" => $page],
            $extra
        );

    return "?" . http_build_query($params);
}
?>