<?php
session_start();
require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ./resident.php");
    exit();
}

$id = intval($_POST["resident_id"] ?? 0);
if ($id <= 0) {
    header("Location: ../resident.php");
    exit();
}

// ── Fetch the resident to archive ─────────────────────
$fetch = mysqli_prepare($conn, "SELECT * FROM residents WHERE ID = ?");
mysqli_stmt_bind_param($fetch, "i", $id);
mysqli_stmt_execute($fetch);
$result = mysqli_stmt_get_result($fetch);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    $_SESSION["edit_error"] = "Resident not found.";
    header("Location: ../resident.php");
    exit();
}

// ── Insert into archive ───────────────────────────────
$sql = "INSERT INTO archive (
            first_name, middle_name, last_name, civil_status,
            birthdate, age, birthplace, sex,
            address, contact_num,
            emergency_cont, emergency_cont_num, emergency_cont_rel,
            socials, disablity_type, disability_remarks, resident_type,
            guardian_name, guardian_cont_num, guardian_rel,
            father_name, mother_name, spouse_name,
            pwdid_num, control_num, idissue_date, idexpiration_date,
            profile, med_cert, status
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?
        )";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION["edit_error"] = "Archive DB error: " . mysqli_error($conn);
    header("Location: ../editResident.php?id=$id");
    exit();
}

$age = intval($r['age']);

mysqli_stmt_bind_param($stmt, "sssssissssssssssssssssssssssss",
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
    $r['med_cert'],
    $r['status']
);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION["edit_error"] = "Failed to archive: " . mysqli_stmt_error($stmt);
    header("Location: ../editResident.php?id=$id");
    exit();
}

// ── Delete from residents ─────────────────────────────
$del = mysqli_prepare($conn, "DELETE FROM residents WHERE ID = ?");
mysqli_stmt_bind_param($del, "i", $id);

if (mysqli_stmt_execute($del)) {
    auditLog(
        $conn,
        "ARCHIVE",
        "Residents",
        $id,
        "Archived resident: " . $r["first_name"] . " " . $r["last_name"]
    );

    $_SESSION["arch_success"] = "Resident archived successfully.";
    header("Location: ../resident.php");
    exit();
} else {
    $_SESSION["edit_error"] = "Archived but failed to remove from residents: " . mysqli_stmt_error($del);
    header("Location: ../editResident.php?id=$id");
    exit();
}
?>