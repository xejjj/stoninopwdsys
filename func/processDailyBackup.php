<?php
require_once(__DIR__ . "/db.php");

$backupFolder = __DIR__ . "/../backups/";

if (!is_dir($backupFolder)) {
    mkdir($backupFolder, 0777, true);
}

$today = date("Y-m-d");

$existingBackups = glob($backupFolder . "StoNinoPWDSys_daily_backup_" . $today . "*.sql");

if (count($existingBackups) > 0) {
    return;
}

$tables = [
    "admincreds",
    "residents",
    "resident_contacts",
    "resident_emergency_contacts",
    "resident_disabilities",
    "resident_family_members",
    "audit_logs"
];

$backupName = "StoNinoPWDSys_daily_backup_" . date("Y-m-d_H-i-s") . ".sql";
$backupPath = $backupFolder . $backupName;

$sqlScript = "";

foreach ($tables as $table) {

    $createTable = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
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

file_put_contents($backupPath, $sqlScript);

/* =========================
   KEEP ONLY 30 BACKUPS
========================= */

$backupFiles = glob($backupFolder . "*.sql");

/* SORT BY OLDEST FIRST */

usort($backupFiles, function($a, $b) {

    return filemtime($a) - filemtime($b);

});

/* DELETE EXTRA FILES */

if (count($backupFiles) > 30) {

    $filesToDelete =
        count($backupFiles) - 30;

    for ($i = 0; $i < $filesToDelete; $i++) {

        unlink($backupFiles[$i]);
    }
}

?>