<?php
session_start();
require_once("db.php");
require_once("audit.php");

$tables = [
    "admincreds",
    "residents",
    "archive",
    "rejected",
    "audit_logs"
];

$backupName = "backup_" . date("Y-m-d_H-i-s") . ".sql";
$backupFolder = "../backups/";

if (!is_dir($backupFolder)) {
    mkdir($backupFolder, 0777, true);
}

$backupPath = $backupFolder . $backupName;
$sqlScript = "";

foreach ($tables as $table) {
    $createTable = mysqli_query($conn, "SHOW CREATE TABLE `$table`");

    if (!$createTable) {
        $_SESSION['error'] = "Backup failed while reading table: $table";
        header("Location: ../system.php");
        exit;
    }

    $tableResult = mysqli_fetch_row($createTable);

    $sqlScript .= "\nDROP TABLE IF EXISTS `$table`;\n";
    $sqlScript .= $tableResult[1] . ";\n\n";

    $rows = mysqli_query($conn, "SELECT * FROM `$table`");

    while ($row = mysqli_fetch_assoc($rows)) {
        $columns = array_keys($row);

        $values = array_map(function($value) use ($conn) {
            if ($value === null) {
                return "NULL";
            }

            return "'" . mysqli_real_escape_string($conn, $value) . "'";
        }, array_values($row));

        $sqlScript .= "INSERT INTO `$table` (`" .
            implode("`,`", $columns) .
            "`) VALUES (" .
            implode(",", $values) .
            ");\n";
    }

    $sqlScript .= "\n";
}

if (file_put_contents($backupPath, $sqlScript) === false) {
    $_SESSION['error'] = "Backup failed. Cannot save file to backups folder.";
    header("Location: ../system.php");
    exit;
}

auditLog(
    $conn,
    "BACKUP",
    "System",
    null,
    "Created database backup: $backupName"
);

$_SESSION['success'] = "Database backup created successfully!";
header("Location: ../system.php");
exit;
?>