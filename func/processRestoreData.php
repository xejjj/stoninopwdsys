<?php
session_start();

require_once("db.php");
require_once("audit.php");

if (isset($_POST['restore'])) {

    if ($_FILES['backup_file']['error'] == 0) {

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