<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("func/db.php");

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Pending';
$allowed_filters = ['Pending', 'Under Review', 'Needs Correction', 'Rejected', 'All'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'Pending';
}

$limit = 1; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$main_table = "residents";

if ($filter === 'Rejected') {
    // Show only the rejected table
    $count_sql = "SELECT COUNT(*) as total FROM rejected";
    $data_sql = "SELECT * FROM rejected ORDER BY id DESC LIMIT $limit OFFSET $offset";

} elseif ($filter === 'All') {
    // NEW: Count EVERYTHING in both tables for pagination
    $count_sql = "SELECT SUM(t) as total FROM (
                    SELECT COUNT(*) as t FROM $main_table 
                    UNION ALL 
                    SELECT COUNT(*) as t FROM rejected
                  ) as combined_count";

    // NEW: Select EVERYTHING from residents (including Active) and rejected
    $data_sql = "(SELECT * FROM $main_table) 
                 UNION ALL 
                 (SELECT * FROM rejected) 
                 ORDER BY id DESC LIMIT $limit OFFSET $offset";

} else {
    // Specific tabs (Pending, Under Review, Needs Correction)
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

// BOX 1: PENDING REVIEW (We keep this filtered so you know what work is left)
$res_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM $main_table WHERE status IN ('Pending', 'Under Review', 'Needs Correction')");
$pending_count = mysqli_fetch_assoc($res_pending)['total'] ?? 0;

// BOX 2: REJECTED
$res_rejected = mysqli_query($conn, "SELECT COUNT(*) as total FROM rejected");
$rejected_count = mysqli_fetch_assoc($res_rejected)['total'] ?? 0;

// BOX 3: TOTAL SUBMISSIONS (NEW: Includes ALL residents in main table + rejected table)
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