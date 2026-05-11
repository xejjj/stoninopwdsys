<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $_SESSION["login_error"] = "Please fill in all fields.";
        header("Location: ../login.php");
        exit();
    }

    $sql  = "SELECT id, username, password, role FROM admincreds WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $_SESSION["login_error"] = "Database error: " . mysqli_error($conn);
        header("Location: ../login.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        $storedPassword = $user["password"];
        $isHashed = strlen($storedPassword) === 60 && strpos($storedPassword, '$2y$') === 0;

        $passwordMatch = $isHashed
            ? password_verify($password, $storedPassword)
            : ($password === $storedPassword);

        if ($passwordMatch) {
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"]     = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: ../dashboard.php");
            } 
            
            else {
                $_SESSION["login_error"] = "Access denied. Admins only.";
                header("Location: ../login.php");
            }
            exit();
        } 
        
        else {
            $_SESSION["login_error"] = "Incorrect password.";
            header("Location: ../login.php");
            exit();
        }

    } 
    
    else {
        $_SESSION["login_error"] = "Invalid username or password.";
        header("Location: ../login.php");
        exit();
    }
}
?>