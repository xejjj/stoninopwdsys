<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);
if ($id <= 0) {
    header("Location: archive.php");
    exit();
}

// ── Fetch profile path so we can delete the image file too ──
$fetch = mysqli_prepare($conn, "SELECT profile FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($fetch, "i", $id);
mysqli_stmt_execute($fetch);
mysqli_stmt_bind_result($fetch, $profile_path);
mysqli_stmt_fetch($fetch);
mysqli_stmt_close($fetch);

// ── Permanently delete from archive ──────────────────
$del = mysqli_prepare($conn, "DELETE FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($del, "i", $id);

if (mysqli_stmt_execute($del)) {
    // Optionally remove the profile image file if it exists
    if (!empty($profile_path) && file_exists($profile_path)) {
        unlink($profile_path);
    }
    $_SESSION["arch_success"] = "Resident permanently deleted.";
    header("Location: archive.php");
    exit();
} else {
    $_SESSION["arch_error"] = "Failed to delete: " . mysqli_stmt_error($del);
    header("Location: archive.php");
    exit();
}
?>