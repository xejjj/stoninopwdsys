<?php
session_start();
require_once("db.php");
require_once("audit.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $role = $_POST["role"] ?? "encoder";

    if ($full_name !== "" && $username !== "" && $password !== "" && $password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO admincreds (full_name, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $full_name, $username, $hashed_password, $role);

        if (mysqli_stmt_execute($stmt)) {
            $new_id = mysqli_insert_id($conn);

            auditLog(
                $conn,
                "CREATE",
                "Accounts",
                $new_id,
                "Added account: $full_name ($username) as $role"
            );
        }
    }
}

header("Location: ../account.php");
exit();
?>