<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);

if ($id <= 0) {
    header("Location: ../archive.php");
    exit();
}

// ── Fetch file paths so we can delete uploaded files too ──
$fetch = mysqli_prepare($conn, "SELECT profile, med_cert FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($fetch, "i", $id);
mysqli_stmt_execute($fetch);
mysqli_stmt_bind_result($fetch, $profile_path, $med_cert_path);
mysqli_stmt_fetch($fetch);
mysqli_stmt_close($fetch);

// ── Permanently delete from archive ──────────────────
$del = mysqli_prepare($conn, "DELETE FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($del, "i", $id);

if (mysqli_stmt_execute($del)) {

    // Delete profile picture
    if (!empty($profile_path)) {
        $profile_file = "../" . $profile_path;

        if (file_exists($profile_file)) {
            unlink($profile_file);
        }
    }

    // Delete medical certificate
    if (!empty($med_cert_path)) {
        $med_cert_file = "../" . $med_cert_path;

        if (file_exists($med_cert_file)) {
            unlink($med_cert_file);
        }
    }

    $_SESSION["arch_success"] = "Resident permanently deleted.";
    header("Location: ../archive.php");
    exit();

} else {
    $_SESSION["arch_error"] = "Failed to delete: " . mysqli_stmt_error($del);
    header("Location: ../archive.php");
    exit();
}
?>