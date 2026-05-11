<?php
session_start();
require_once("func/db.php");

// Fetch counts for the top stat cards
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE status = 'Pending'");
$pending_count = mysqli_fetch_assoc($q_pending)['cnt'] ?? 0;

$q_rejected = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE status = 'Rejected'");
$rejected_count = mysqli_fetch_assoc($q_rejected)['cnt'] ?? 0;

$q_total = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents");
$total_count = mysqli_fetch_assoc($q_total)['cnt'] ?? 0;

// Handle filter logic
$filter = $_GET['filter'] ?? 'Pending';
$where_clause = "";

if ($filter === 'Pending') {
    $where_clause = " WHERE status = 'Pending'";
} elseif ($filter === 'Rejected') {
    $where_clause = " WHERE status = 'Rejected'";
}

// Pagination logic
$limit = 1; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM residents" . $where_clause);
$total_records = mysqli_fetch_assoc($count_query)['total'] ?? 0;
$total_pages = ceil($total_records / $limit);

$offset = ($page - 1) * $limit;

// Fetch the specific submissions for the current page
$sql = "SELECT * FROM residents" . $where_clause . " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$submissions = mysqli_query($conn, $sql);

// Helper to color-code disability badges
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