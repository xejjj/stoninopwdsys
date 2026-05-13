<?php
session_start();
require_once("db.php");
require_once("audit.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["user_id"] ?? 0);

    if ($id > 0) {
        $fetch = mysqli_prepare($conn, "SELECT full_name, username, role FROM admincreds WHERE id = ?");
        mysqli_stmt_bind_param($fetch, "i", $id);
        mysqli_stmt_execute($fetch);
        $result = mysqli_stmt_get_result($fetch);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $sql = "DELETE FROM admincreds WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                auditLog(
                    $conn,
                    "DELETE",
                    "Accounts",
                    $id,
                    "Deleted account: {$user['full_name']} ({$user['username']}), role: {$user['role']}"
                );
            }
        }
    }
}

header("Location: ../account.php");
exit();
?>