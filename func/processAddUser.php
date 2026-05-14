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

    $full_name = trim($_POST["full_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $role = $_POST["role"] ?? "encoder";

    if (
        $full_name === "" ||
        $username === "" ||
        $password === "" ||
        $password !== $confirm_password
    ) {
        $_SESSION["error"] =
            "Please ensure all fields are filled and passwords match.";

        header("Location: ../account.php");
        exit();
    }

    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM admincreds
         WHERE username = ?"
    );

    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);

    $check_result =
        mysqli_stmt_get_result($check);

    if (mysqli_num_rows($check_result) > 0) {

        $_SESSION["error"] =
            "The username '$username' is already taken.";

        header("Location: ../account.php");
        exit();
    }

    $hashed_password =
        password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO admincreds (
            full_name,
            username,
            password,
            role
        ) VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $full_name,
        $username,
        $hashed_password,
        $role
    );

    if (mysqli_stmt_execute($stmt)) {

        $new_id =
            mysqli_insert_id($conn);

        auditLog(
            $conn,
            "CREATE",
            "Accounts",
            null,
            "Added account: $full_name ($username) as $role"
        );

        $_SESSION["success"] =
            "User added successfully.";

    } else {

        $_SESSION["error"] =
            "Failed to add user.";
    }
}

header("Location: ../account.php");
exit();
?>