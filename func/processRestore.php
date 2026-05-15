<?php
session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);

if ($id <= 0) {
    $_SESSION["arch_error"] = "Invalid resident selected.";
    header("Location: ../archive.php");
    exit();
}

/* Check if archived resident exists */
$check = mysqli_prepare(
    $conn,
    "SELECT ID, application_status
     FROM residents
     WHERE ID = ?
     AND record_status = 'archived'"
);

mysqli_stmt_bind_param($check, "i", $id);
mysqli_stmt_execute($check);

$check_result = mysqli_stmt_get_result($check);
$resident = mysqli_fetch_assoc($check_result);

if (!$resident) {
    $_SESSION["arch_error"] = "Archived resident not found or already restored.";
    header("Location: ../archive.php");
    exit();
}

/* Restore resident */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE residents
     SET record_status = 'active'
     WHERE ID = ?
     AND record_status = 'archived'"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {

    auditLog(
        $conn,
        "RESTORE",
        "Archive",
        $id,
        "Resident restored from archive"
    );

    $_SESSION["arch_success"] = "Resident restored successfully.";

} else {

    $_SESSION["arch_error"] = "Failed to restore resident.";
}

header("Location: ../archive.php");
exit();
?><?php
session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);

if ($id <= 0) {
    $_SESSION["arch_error"] = "Invalid resident selected.";
    header("Location: ../archive.php");
    exit();
}

/* Check if archived resident exists */
$check = mysqli_prepare(
    $conn,
    "SELECT ID, application_status
     FROM residents
     WHERE ID = ?
     AND record_status = 'archived'"
);

mysqli_stmt_bind_param($check, "i", $id);
mysqli_stmt_execute($check);

$check_result = mysqli_stmt_get_result($check);
$resident = mysqli_fetch_assoc($check_result);

if (!$resident) {
    $_SESSION["arch_error"] = "Archived resident not found or already restored.";
    header("Location: ../archive.php");
    exit();
}

/* Restore resident */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE residents
     SET record_status = 'active'
     WHERE ID = ?
     AND record_status = 'archived'"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {

    auditLog(
        $conn,
        "RESTORE",
        "Archive",
        $id,
        "Resident restored from archive"
    );

    $_SESSION["arch_success"] = "Resident restored successfully.";

} else {

    $_SESSION["arch_error"] = "Failed to restore resident.";
}

header("Location: ../archive.php");
exit();
?>