<?php
session_start();
require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);

if ($id <= 0) {
    header("Location: ../archive.php");
    exit();
}

// Fetch file paths and resident name before deleting
$fetch = mysqli_prepare($conn, "SELECT first_name, last_name, profile, med_cert FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($fetch, "i", $id);
mysqli_stmt_execute($fetch);
$result = mysqli_stmt_get_result($fetch);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    $_SESSION["arch_error"] = "Archived resident not found.";
    header("Location: ../archive.php");
    exit();
}

$del = mysqli_prepare($conn, "DELETE FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($del, "i", $id);

if (mysqli_stmt_execute($del)) {

    if (!empty($r["profile"])) {
        $profile_file = "../" . $r["profile"];
        if (file_exists($profile_file)) {
            unlink($profile_file);
        }
    }

    if (!empty($r["med_cert"])) {
        $med_cert_file = "../" . $r["med_cert"];
        if (file_exists($med_cert_file)) {
            unlink($med_cert_file);
        }
    }

    auditLog(
        $conn,
        "DELETE",
        "Archive",
        $id,
        "Permanently deleted archived resident: " . $r["first_name"] . " " . $r["last_name"]
    );

    $_SESSION["arch_success"] = "Resident permanently deleted.";
    header("Location: ../archive.php");
    exit();
} else {
    $_SESSION["arch_error"] = "Failed to delete: " . mysqli_stmt_error($del);
    header("Location: ../archive.php");
    exit();
}
?>