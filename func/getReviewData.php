<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("func/db.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

/* =========================
   FILTERS
========================= */

$filter =
    $_GET['filter']
    ?? 'Under Review';

$allowed_filters = [
    'Under Review',
    'Needs Correction',
    'Rejected',
    'All'
];

if (!in_array($filter, $allowed_filters)) {
    $filter = 'Under Review';
}

/* =========================
   PAGINATION
========================= */

$limit = 1;

$page =
    isset($_GET['page']) &&
    is_numeric($_GET['page'])
    ? (int)$_GET['page']
    : 1;

if ($page < 1) {
    $page = 1;
}

$offset =
    ($page - 1) * $limit;

/* =========================
   WHERE
========================= */

$where = [];

$where[] =
    "record_status != 'archived'";

if ($filter === 'Under Review') {

    $where[] =
        "application_status = 'under review'";

} elseif ($filter === 'Needs Correction') {

    $where[] =
        "application_status = 'needs correction'";

} elseif ($filter === 'Rejected') {

    $where[] =
        "application_status = 'rejected'";
}

$where_sql =
    "WHERE " .
    implode(" AND ", $where);

/* =========================
   COUNT
========================= */

$count_sql = "
SELECT COUNT(*) as total
FROM residents
$where_sql
";

$count_res =
    mysqli_query($conn, $count_sql);

$total_records =
    mysqli_fetch_assoc($count_res)['total']
    ?? 0;

$total_pages =
    $total_records > 0
    ? ceil($total_records / $limit)
    : 0;

/* =========================
   DATA
========================= */

$data_sql = "
SELECT
    residents.*,

    resident_contacts.contact_num,
    resident_contacts.socials,

    resident_emergency_contacts.name AS emergency_name,
    resident_emergency_contacts.contact_num AS emergency_number,
    resident_emergency_contacts.relationship AS emergency_relation,

    GROUP_CONCAT(
        DISTINCT resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type,

    MAX(resident_disabilities.notes) AS disability_remarks,

    MAX(CASE
        WHEN resident_family_members.relationship = 'Father'
        THEN resident_family_members.name
    END) AS father_name,

    MAX(CASE
        WHEN resident_family_members.relationship = 'Mother'
        THEN resident_family_members.name
    END) AS mother_name,

    MAX(CASE
        WHEN resident_family_members.relationship = 'Spouse'
        THEN resident_family_members.name
    END) AS spouse_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.name
    END) AS guardian_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.relationship
    END) AS guardian_rel,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.contact_num
    END) AS guardian_number

FROM residents

LEFT JOIN resident_contacts
ON residents.ID = resident_contacts.resident_id

LEFT JOIN resident_emergency_contacts
ON residents.ID = resident_emergency_contacts.resident_id

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id

LEFT JOIN resident_family_members
ON residents.ID = resident_family_members.resident_id

$where_sql

GROUP BY residents.ID

ORDER BY residents.ID DESC

LIMIT $limit OFFSET $offset
";

$submissions = mysqli_query($conn, $data_sql);

if (!$submissions) {
    die("Query Failed: " . mysqli_error($conn));
}

/* =========================
   STATS
========================= */

$res_under_review =
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) as total
        FROM residents
        WHERE application_status IN (
            'under review',
            'needs correction'
        )
        AND record_status != 'archived'
        "
    );

$under_review_count =
    mysqli_fetch_assoc(
        $res_under_review
    )['total'] ?? 0;

$res_rejected =
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) as total
        FROM residents
        WHERE application_status = 'rejected'
        AND record_status != 'archived'
        "
    );

$rejected_count =
    mysqli_fetch_assoc(
        $res_rejected
    )['total'] ?? 0;

$res_total =
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*) as total
        FROM residents
        WHERE record_status != 'archived'
        "
    );

$total_count =
    mysqli_fetch_assoc(
        $res_total
    )['total'] ?? 0;

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
    ];

    $key =
        strtolower(
            trim(
                explode(",", $type)[0]
            )
        );

    return $map[$key]
        ?? "badge-physical";
}
?>