<?php
session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if (($_SESSION["role"] ?? "") !== "admin") {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_POST['restore'])) {

    if ($_FILES['backup_file']['error'] == 0) {

        // ── Validate file type ────────────────────────
        $original_name = $_FILES['backup_file']['name'];
        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if ($ext !== 'sql') {
            $_SESSION['error'] = "Invalid file type. Only .sql backup files are allowed.";
            header("Location: ../system.php");
            exit;
        }

        // ── Validate file size (max 50MB) ─────────────
        $max_size = 50 * 1024 * 1024;
        if ($_FILES['backup_file']['size'] > $max_size) {
            $_SESSION['error'] = "File too large. Maximum allowed size is 50MB.";
            header("Location: ../system.php");
            exit;
        }

        $file = $_FILES['backup_file']['tmp_name'];

        $sql = file_get_contents($file);

        mysqli_report(MYSQLI_REPORT_OFF);

        if (mysqli_multi_query($conn, $sql)) {

            do {

                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }

            } while (mysqli_more_results($conn) && mysqli_next_result($conn));

            auditLog(
                $conn,
                "RESTORE",
                "System",
                null,
                "Restored database backup"
            );

            $_SESSION['success'] = "Database restored successfully!";

        } else {

            $_SESSION['error'] = "Restore failed: " . mysqli_error($conn);

        }

    } else {

        $_SESSION['error'] = "Please select a valid SQL file.";

    }

}

header("Location: ../system.php");
exit;
?>