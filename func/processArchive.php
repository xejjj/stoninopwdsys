<?php
session_start();
require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../resident.php");
    exit();
}

$id = intval($_POST["resident_id"] ?? 0);

if ($id <= 0) {
    header("Location: ../resident.php");
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE residents
     SET record_status = 'archived'
     WHERE ID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    auditLog(
        $conn,
        "ARCHIVE",
        "Residents",
        $id,
        "Archived resident ID: " . $id
    );

    $_SESSION["arch_success"] =
        "Resident archived successfully.";

    header("Location: ../resident.php");
    exit();
}

$_SESSION["edit_error"] = "Failed to archive resident.";
header("Location: ../editResident.php?id=" . $id);
exit();
?>