<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "admin"; 
    
    if (!empty($username) && !empty($password)) {
        // Double check they match on the backend
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admincreds (username, password, role) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $role);
            mysqli_stmt_execute($stmt);
        }
    }
}
header("Location: ../account.php");
exit();
?>