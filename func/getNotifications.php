<?php

$today = date("Y-m-d");

/* =========================
   EXPIRED IDS
========================= */

$expired_sql = "
SELECT COUNT(*) AS total
FROM residents
WHERE idexpiration_date IS NOT NULL
AND idexpiration_date != ''
AND DATE(idexpiration_date) < CURDATE()
AND record_status != 'archived'
";

$expired_result = mysqli_query($conn, $expired_sql);
$expired_ids_count = mysqli_fetch_assoc($expired_result)["total"] ?? 0;


/* =========================
   UNDER REVIEW
========================= */

$review_sql = "
SELECT COUNT(*) AS total
FROM residents
WHERE application_status = 'under review'
AND record_status != 'archived'
";

$review_result = mysqli_query($conn, $review_sql);
$review_count = mysqli_fetch_assoc($review_result)["total"] ?? 0;


/* =========================
   MISSING MED CERT
========================= */

$medcert_sql = "
SELECT COUNT(*) AS total
FROM residents
WHERE (med_cert IS NULL OR med_cert = '')
AND record_status != 'archived'
AND application_status != 'approved'
";

$medcert_result = mysqli_query($conn, $medcert_sql);
$missing_medcert_count = mysqli_fetch_assoc($medcert_result)["total"] ?? 0;


/* =========================
   EXPIRING SOON
========================= */

$expiring_soon_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM residents
     WHERE idexpiration_date IS NOT NULL
     AND idexpiration_date != ''
     AND DATE(idexpiration_date)
     BETWEEN CURDATE()
     AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
     AND record_status = 'active'"
);

$expiring_soon_count =
    mysqli_fetch_assoc($expiring_soon_result)["total"] ?? 0;


/* =========================
   BACKUP REMINDER
========================= */

$backupFolder = "backups/";
$backupReminder = false;

$backupFiles = glob($backupFolder . "*.sql");

if ($backupFiles && count($backupFiles) > 0) {

    usort($backupFiles, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    $latestBackup = filemtime($backupFiles[0]);

    if ((time() - $latestBackup) > 86400) {
        $backupReminder = true;
    }

} else {
    $backupReminder = true;
}


/* =========================
   TOTAL NOTIFICATIONS
========================= */

$notification_count =
    $expired_ids_count +
    $expiring_soon_count +
    $review_count +
    $missing_medcert_count;

if ($backupReminder) {
    $notification_count++;
}

?>