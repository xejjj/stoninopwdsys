<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["user_id"]);
    $username = trim($_POST["username"]);
    $new_password = $_POST["password"]; 
    $confirm_password = $_POST["confirm_password"]; 
    
    if ($id > 0 && !empty($username)) {
        if (!empty($new_password)) {
            // Check if passwords match before updating
            if ($new_password === $confirm_password) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE admincreds SET username = ?, password = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssi", $username, $hashed, $id);
                mysqli_stmt_execute($stmt);
            }
        } else {
            // Update ONLY username if password fields were left blank
            $sql = "UPDATE admincreds SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $username, $id);
            mysqli_stmt_execute($stmt);
        }
    }
}
header("Location: ../account.php");
exit();
?>