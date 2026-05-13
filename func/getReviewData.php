<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("func/db.php");

// Changed default filter to Under Review
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Under Review';
$allowed_filters = ['Under Review', 'Needs Correction', 'Rejected', 'All'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'Under Review';
}

$limit = 1; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$main_table = "residents";

if ($filter === 'Rejected') {
    $count_sql = "SELECT COUNT(*) as total FROM rejected";
    $data_sql = "SELECT * FROM rejected ORDER BY id DESC LIMIT $limit OFFSET $offset";

} elseif ($filter === 'All') {
    $count_sql = "SELECT SUM(t) as total FROM (
                    SELECT COUNT(*) as t FROM $main_table 
                    UNION ALL 
                    SELECT COUNT(*) as t FROM rejected
                  ) as combined_count";

    $data_sql = "(SELECT * FROM $main_table) 
                 UNION ALL 
                 (SELECT * FROM rejected) 
                 ORDER BY id DESC LIMIT $limit OFFSET $offset";

} else {
    $count_sql = "SELECT COUNT(*) as total FROM $main_table WHERE status = '$filter'";
    $data_sql = "SELECT * FROM $main_table WHERE status = '$filter' ORDER BY id DESC LIMIT $limit OFFSET $offset";
}

$count_res = mysqli_query($conn, $count_sql);
$total_records = mysqli_fetch_assoc($count_res)['total'] ?? 0;
$total_pages = $total_records > 0 ? ceil($total_records / $limit) : 0;

$submissions = mysqli_query($conn, $data_sql);

if (!$submissions) {
    die("Query Failed: " . mysqli_error($conn));
}

// BOX 1: UNDER REVIEW (Counts Under Review and Needs Correction)
$res_under_review = mysqli_query($conn, "SELECT COUNT(*) as total FROM $main_table WHERE status IN ('Under Review', 'Needs Correction')");
$under_review_count = mysqli_fetch_assoc($res_under_review)['total'] ?? 0;

$res_rejected = mysqli_query($conn, "SELECT COUNT(*) as total FROM rejected");
$rejected_count = mysqli_fetch_assoc($res_rejected)['total'] ?? 0;

$res_main_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM $main_table");
$main_total = mysqli_fetch_assoc($res_main_total)['total'] ?? 0;
$total_count = $main_total + $rejected_count;

function badgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"     => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
    ];
    $key = strtolower(trim(explode(",", $type)[0]));
    return $map[$key] ?? "badge-physical";
}
?>