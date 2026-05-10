<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);
if ($id <= 0) {
    header("Location: archive.php");
    exit();
}

// ── Fetch the archived resident ───────────────────────
$fetch = mysqli_prepare($conn, "SELECT * FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($fetch, "i", $id);
mysqli_stmt_execute($fetch);
$result = mysqli_stmt_get_result($fetch);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    $_SESSION["arch_error"] = "Archived resident not found.";
    header("Location: archive.php");
    exit();
}

// ── Insert back into residents ────────────────────────
$sql = "INSERT INTO residents (
            first_name, middle_name, last_name, civil_status,
            birthdate, age, birthplace, sex,
            address, contact_num,
            emergency_cont, emergency_cont_num, emergency_cont_rel,
            socials, disablity_type, disability_remarks, resident_type,
            guardian_name, guardian_cont_num, guardian_rel,
            father_name, mother_name, spouse_name,
            pwdid_num, control_num, idissue_date, idexpiration_date,
            profile, status
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?
        )";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION["arch_error"] = "Restore DB error: " . mysqli_error($conn);
    header("Location: archive.php");
    exit();
}

$age = intval($r['age']);

mysqli_stmt_bind_param($stmt, "sssssisssssssssssssssssssssss",
    $r['first_name'],
    $r['middle_name'],
    $r['last_name'],
    $r['civil_status'],
    $r['birthdate'],
    $age,
    $r['birthplace'],
    $r['sex'],
    $r['address'],
    $r['contact_num'],
    $r['emergency_cont'],
    $r['emergency_cont_num'],
    $r['emergency_cont_rel'],
    $r['socials'],
    $r['disablity_type'],
    $r['disability_remarks'],
    $r['resident_type'],
    $r['guardian_name'],
    $r['guardian_cont_num'],
    $r['guardian_rel'],
    $r['father_name'],
    $r['mother_name'],
    $r['spouse_name'],
    $r['pwdid_num'],
    $r['control_num'],
    $r['idissue_date'],
    $r['idexpiration_date'],
    $r['profile'],
    $r['status']
);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION["arch_error"] = "Failed to restore: " . mysqli_stmt_error($stmt);
    header("Location: archive.php");
    exit();
}

// ── Delete from archive ───────────────────────────────
$del = mysqli_prepare($conn, "DELETE FROM archive WHERE ID = ?");
mysqli_stmt_bind_param($del, "i", $id);

if (mysqli_stmt_execute($del)) {
    $_SESSION["arch_success"] = "Resident restored successfully.";
    header("Location: archive.php");
    exit();
} else {
    $_SESSION["arch_error"] = "Restored but failed to remove from archive: " . mysqli_stmt_error($del);
    header("Location: archive.php");
    exit();
}
?>