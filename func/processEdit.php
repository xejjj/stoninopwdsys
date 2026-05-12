<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../resident.php");
    exit();
}

$id = intval($_POST["resident_id"] ?? 0);
if ($id <= 0) {
    header("Location: ../resident.php");
    exit();
}

// ── Personal Information ──────────────────────────────
$first_name   = trim($_POST["first_name"]   ?? "");
$middle_name  = trim($_POST["middle_name"]  ?? "");
$last_name    = trim($_POST["last_name"]    ?? "");
$civil_status = trim($_POST["civil_status"] ?? "");
$birthdate    = trim($_POST["dob"]          ?? "");
$birthplace   = trim($_POST["pob"]          ?? "");
$age          = intval($_POST["age"]        ?? 0);
$sex          = strtolower(trim($_POST["sex"] ?? ""));

// ── Contact & Address ─────────────────────────────────
$contact_num        = trim($_POST["contact_number"]     ?? "");
$emergency_cont     = trim($_POST["emergency_name"]     ?? "");
$emergency_cont_num = trim($_POST["emergency_number"]   ?? "");
$emergency_cont_rel = trim($_POST["emergency_relation"] ?? "");
$socials            = trim($_POST["account_name"]       ?? "");
$address            = trim($_POST["address"]            ?? "");

// ── Disability ────────────────────────────────────────
$disability_type    = isset($_POST["disability_type"])
    ? implode(", ", $_POST["disability_type"])
    : "";
$disability_remarks = trim($_POST["remarks"] ?? "");

// ── Resident Type ─────────────────────────────────────
$guardian_name   = trim($_POST["guardian_name"]   ?? "");
$guardian_number = trim($_POST["guardian_number"] ?? "");
$guardian_rel    = trim($_POST["child_relation"]  ?? "");
$resident_type   = !empty($guardian_name) ? "CWD" : "PWD";

// ── Family Information ────────────────────────────────
$father_name = trim($_POST["father_name"] ?? "");
$mother_name = trim($_POST["mother_name"] ?? "");
$spouse_name = trim($_POST["spouse_name"] ?? "");

// ── ID Registration ───────────────────────────────────
$pwdid_num         = trim($_POST["pwd_id"]          ?? "");
$control_num       = trim($_POST["control_id"]      ?? "");
$idissue_date      = trim($_POST["date_issued"]     ?? "");
$idexpiration_date = trim($_POST["expiration_date"] ?? "");

// ── Status ────────────────────────────────────────────
$status = trim($_POST["status"] ?? "Active");

// ── Profile Picture Upload ────────────────────────────
$cur = mysqli_query($conn, "SELECT profile, med_cert FROM residents WHERE ID = $id");
$cur_row = mysqli_fetch_assoc($cur);

$profile  = $cur_row["profile"] ?? "";
$med_cert = $cur_row["med_cert"] ?? "";

if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === 0) {
    $upload_dir = "../uploads/profiles/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $ext     = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif", "webp"];

    if (!in_array($ext, $allowed)) {
        $_SESSION["edit_error"] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
        header("Location: ../editResident.php?id=$id");
        exit();
    }

    $base_name = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "_", $first_name . "_" . $last_name));
    $safe_name =
    $base_name . "_profile_" . time() . "." . $ext;
    $target    = $upload_dir . $safe_name;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target)) {
        $profile = "uploads/profiles/" . $safe_name;
    }
}

// ── Medical Certificate Upload ─────────────────────
if (isset($_FILES["med_cert"]) && $_FILES["med_cert"]["error"] === 0) {

    $upload_dir = "../uploads/medical_certificates/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = strtolower(pathinfo($_FILES["med_cert"]["name"], PATHINFO_EXTENSION));

    $allowed = ["pdf", "jpg", "jpeg", "png"];

    if (!in_array($ext, $allowed)) {
        $_SESSION["edit_error"] = "Invalid medical certificate file.";
        header("Location: ../editResident.php?id=$id");
        exit();
    }

    $base_name = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "_", $first_name . "_" . $last_name));
    $safe_name =
    $base_name . "_medcert_" . time() . "." . $ext;

    $target = $upload_dir . $safe_name;

    if (move_uploaded_file($_FILES["med_cert"]["tmp_name"], $target)) {
        $med_cert = "uploads/medical_certificates/" . $safe_name;
    }
}

// ── UPDATE ────────────────────────────────────────────
$sql = "UPDATE residents SET
            first_name        = ?,
            middle_name       = ?,
            last_name         = ?,
            civil_status      = ?,
            birthdate         = ?,
            age               = ?,
            birthplace        = ?,
            sex               = ?,
            address           = ?,
            contact_num       = ?,
            emergency_cont    = ?,
            emergency_cont_num= ?,
            emergency_cont_rel= ?,
            socials           = ?,
            disablity_type    = ?,
            disability_remarks= ?,
            resident_type     = ?,
            guardian_name     = ?,
            guardian_cont_num = ?,
            guardian_rel      = ?,
            father_name       = ?,
            mother_name       = ?,
            spouse_name       = ?,
            pwdid_num         = ?,
            control_num       = ?,
            idissue_date      = ?,
            idexpiration_date = ?,
            profile           = ?,
            med_cert          = ?,
            status            = ?
        WHERE ID = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION["edit_error"] = "Database error: " . mysqli_error($conn);
    header("Location: ../editResident.php?id=$id");
    exit();
}

mysqli_stmt_bind_param($stmt, "sssssissssssssssssssssssssssssi",
    $first_name,
    $middle_name,
    $last_name,
    $civil_status,
    $birthdate,
    $age,
    $birthplace,
    $sex,
    $address,
    $contact_num,
    $emergency_cont,
    $emergency_cont_num,
    $emergency_cont_rel,
    $socials,
    $disability_type,
    $disability_remarks,
    $resident_type,
    $guardian_name,
    $guardian_number,
    $guardian_rel,
    $father_name,
    $mother_name,
    $spouse_name,
    $pwdid_num,
    $control_num,
    $idissue_date,
    $idexpiration_date,
    $profile,
    $med_cert,
    $status,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION["edit_success"] = true;
    header("Location: ../editResident.php?id=$id");
    exit();
} else {
    $_SESSION["edit_error"] = "Failed to update: " . mysqli_stmt_error($stmt);
    header("Location: ../editResident.php?id=$id");
    exit();
}
?>