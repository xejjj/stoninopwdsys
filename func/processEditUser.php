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

    if ($id > 0 && $full_name !== "" && $username !== "") {
        $fetch = mysqli_prepare($conn, "SELECT full_name, username, role FROM admincreds WHERE id = ?");
        mysqli_stmt_bind_param($fetch, "i", $id);
        mysqli_stmt_execute($fetch);
        $result = mysqli_stmt_get_result($fetch);
        $old = mysqli_fetch_assoc($result);

        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);

                $sql = "UPDATE admincreds SET full_name = ?, username = ?, role = ?, password = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $full_name, $username, $role, $hashed, $id);

                if (mysqli_stmt_execute($stmt)) {
                    auditLog(
                        $conn,
                        "UPDATE",
                        "Accounts",
                        $id,
                        "Updated account: {$old['full_name']} ({$old['username']}) to $full_name ($username), role: $role, password changed"
                    );
                }
            }
        } else {
            $sql = "UPDATE admincreds SET full_name = ?, username = ?, role = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $full_name, $username, $role, $id);

            if (mysqli_stmt_execute($stmt)) {
                auditLog(
                    $conn,
                    "UPDATE",
                    "Accounts",
                    $id,
                    "Updated account: {$old['full_name']} ({$old['username']}) to $full_name ($username), role: $role"
                );
            }
        }
    }
}

header("Location: ../account.php");
exit();
?>