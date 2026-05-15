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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../account.php");
    exit();
}

$full_name = trim($_POST["full_name"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";
$role = $_POST["role"] ?? "encoder";

if ($full_name === "" || $username === "" || $password === "" || $password !== $confirm_password) {
    $_SESSION["account_error"] = "Please ensure all fields are filled and passwords match.";
    header("Location: ../account.php");
    exit();
}

$allowed_roles = ["admin", "encoder"];

if (!in_array($role, $allowed_roles)) {
    $role = "encoder";
}

$check = mysqli_prepare(
    $conn,
    "SELECT id FROM admincreds WHERE username = ?"
);

if (!$check) {
    $_SESSION["account_error"] = "Database error while checking username: " . mysqli_error($conn);
    header("Location: ../account.php");
    exit();
}

mysqli_stmt_bind_param($check, "s", $username);
mysqli_stmt_execute($check);

$check_result = mysqli_stmt_get_result($check);

if (mysqli_num_rows($check_result) > 0) {
    $_SESSION["account_error"] = "The username '$username' is already taken.";
    header("Location: ../account.php");
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO admincreds (
        full_name,
        username,
        password,
        role
    ) VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    $_SESSION["account_error"] = "Database error while preparing insert: " . mysqli_error($conn);
    header("Location: ../account.php");
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $full_name,
    $username,
    $hashed_password,
    $role
);

if (mysqli_stmt_execute($stmt)) {
    auditLog(
        $conn,
        "CREATE",
        "Accounts",
        null,
        "Added account: $full_name ($username) as $role"
    );

    $_SESSION["account_success"] = "User added successfully.";
} else {
    $_SESSION["account_error"] = "Failed to add user: " . mysqli_stmt_error($stmt);
}

header("Location: ../account.php");
exit();
?>