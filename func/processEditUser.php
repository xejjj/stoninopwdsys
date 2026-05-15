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
    $full_name = trim($_POST["full_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $role = $_POST["role"] ?? "encoder";
    $new_password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($id <= 0 || $full_name === "" || $username === "") {
        $_SESSION["account_error"] = "Please fill in all required fields.";
        header("Location: ../account.php");
        exit();
    }

    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM admincreds
         WHERE username = ?
         AND id != ?"
    );

    mysqli_stmt_bind_param($check, "si", $username, $id);
    mysqli_stmt_execute($check);

    $checkResult = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION["account_error"] = "Username already exists.";
        header("Location: ../account.php");
        exit();
    }

    $fetch = mysqli_prepare(
        $conn,
        "SELECT full_name, username, role FROM admincreds WHERE id = ?"
    );

    mysqli_stmt_bind_param($fetch, "i", $id);
    mysqli_stmt_execute($fetch);

    $result = mysqli_stmt_get_result($fetch);
    $old = mysqli_fetch_assoc($result);

    if (!$old) {
        $_SESSION["account_error"] = "Account not found.";
        header("Location: ../account.php");
        exit();
    }

    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION["account_error"] = "Passwords do not match.";
            header("Location: ../account.php");
            exit();
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE admincreds
             SET full_name = ?, username = ?, role = ?, password = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $full_name,
            $username,
            $role,
            $hashed,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            auditLog(
                $conn,
                "UPDATE",
                "Accounts",
                null,
                "Updated account: {$old['full_name']} ({$old['username']}) to $full_name ($username), role: $role, password changed"
            );

            $_SESSION["account_success"] = "Account updated successfully.";
        }

    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE admincreds
             SET full_name = ?, username = ?, role = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $full_name,
            $username,
            $role,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            auditLog(
                $conn,
                "UPDATE",
                "Accounts",
                null,
                "Updated account: {$old['full_name']} ({$old['username']}) to $full_name ($username), role: $role"
            );

            $_SESSION["account_success"] = "Account updated successfully.";
        }
    }
}

header("Location: ../account.php");
exit();
?>