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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["user_id"] ?? 0);

    if ($id == ($_SESSION["admin_id"] ?? 0)) {
        $_SESSION["error"] = "You cannot delete your own account.";
        header("Location: ../account.php");
        exit();
    }

    if ($id > 0) {
        $fetch = mysqli_prepare(
            $conn,
            "SELECT full_name, username, role FROM admincreds WHERE id = ?"
        );

        mysqli_stmt_bind_param($fetch, "i", $id);
        mysqli_stmt_execute($fetch);

        $result = mysqli_stmt_get_result($fetch);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $stmt = mysqli_prepare(
                $conn,
                "DELETE FROM admincreds WHERE id = ?"
            );

            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                auditLog(
                    $conn,
                    "DELETE",
                    "Accounts",
                    null,
                    "Deleted account: {$user['full_name']} ({$user['username']}), role: {$user['role']}"
                );

                $_SESSION["success"] = "Account deleted successfully.";
            }
        }
    }
}

header("Location: ../account.php");
exit();
?>