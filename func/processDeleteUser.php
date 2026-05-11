<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST["user_id"]);
    
    if ($id > 0) {
        $sql = "DELETE FROM admincreds WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
}
header("Location: ../account.php");
exit();
?>