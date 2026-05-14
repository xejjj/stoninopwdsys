<?php

session_start();

require_once("db.php");
require_once("audit.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../registration.php");
    exit();
}

/* =========================
   MAIN RESIDENT DATA
========================= */

$first_name   = trim($_POST["first_name"] ?? "");
$middle_name  = trim($_POST["middle_name"] ?? "");
$last_name    = trim($_POST["last_name"] ?? "");
$civil_status = trim($_POST["civil_status"] ?? "");
$birthdate    = trim($_POST["dob"] ?? "");
$birthplace   = trim($_POST["pob"] ?? "");
$sex          = strtolower(trim($_POST["sex"] ?? ""));
$address      = trim($_POST["address"] ?? "");

$pwdid_num         = trim($_POST["pwd_id"] ?? "");
$control_num       = trim($_POST["control_id"] ?? "");
$idissue_date      = trim($_POST["date_issued"] ?? "");
$idexpiration_date = trim($_POST["expiration_date"] ?? "");

$resident_type =
    !empty($_POST["guardian_name"])
    ? "CWD"
    : "PWD";

/* =========================
   STATUS
========================= */

$application_status = "approved";
$record_status      = "active";

/* =========================
   PROFILE IMAGE
========================= */

$profile = "";

if (
    isset($_FILES["profile_pic"])
    && $_FILES["profile_pic"]["error"] === 0
) {

    $upload_dir =
        "../uploads/profiles/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext =
        strtolower(
            pathinfo(
                $_FILES["profile_pic"]["name"],
                PATHINFO_EXTENSION
            )
        );

    $safe_name =
        time() .
        "_profile." .
        $ext;

    $target =
        $upload_dir .
        $safe_name;

    move_uploaded_file(
        $_FILES["profile_pic"]["tmp_name"],
        $target
    );

    $profile =
        "uploads/profiles/" .
        $safe_name;
}

/* =========================
   MED CERT
========================= */

$med_cert = "";

if (
    isset($_FILES["med_cert"])
    && $_FILES["med_cert"]["error"] === 0
) {

    $upload_dir =
        "../uploads/medical_certificates/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext =
        strtolower(
            pathinfo(
                $_FILES["med_cert"]["name"],
                PATHINFO_EXTENSION
            )
        );

    $safe_name =
        time() .
        "_medcert." .
        $ext;

    $target =
        $upload_dir .
        $safe_name;

    move_uploaded_file(
        $_FILES["med_cert"]["tmp_name"],
        $target
    );

    $med_cert =
        "uploads/medical_certificates/" .
        $safe_name;
}

/* =========================
   INSERT RESIDENT
========================= */

$sql = "
INSERT INTO residents (
    first_name,
    middle_name,
    last_name,
    civil_status,
    birthdate,
    birthplace,
    sex,
    address,
    resident_type,
    pwdid_num,
    control_num,
    idissue_date,
    idexpiration_date,
    profile,
    med_cert,
    application_status,
    record_status
)
VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?, ?, ?, ?
)
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssssssss",
    $first_name,
    $middle_name,
    $last_name,
    $civil_status,
    $birthdate,
    $birthplace,
    $sex,
    $address,
    $resident_type,
    $pwdid_num,
    $control_num,
    $idissue_date,
    $idexpiration_date,
    $profile,
    $med_cert,
    $application_status,
    $record_status
);

if (!mysqli_stmt_execute($stmt)) {

    $_SESSION["reg_error"] =
        mysqli_error($conn);

    header("Location: ../registration.php");
    exit();
}

$resident_id =
    mysqli_insert_id($conn);

/* =========================
   CONTACTS
========================= */

$contact_num =
    trim($_POST["contact_number"] ?? "");

$socials =
    trim($_POST["account_name"] ?? "");

if (
    !empty($contact_num)
    || !empty($socials)
) {

    $stmt = mysqli_prepare(
        $conn,
        "
        INSERT INTO resident_contacts (
            resident_id,
            contact_number,
            socials
        )
        VALUES (?, ?, ?)
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $resident_id,
        $contact_num,
        $socials
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   EMERGENCY CONTACT
========================= */

$emergency_name =
    trim($_POST["emergency_name"] ?? "");

$emergency_number =
    trim($_POST["emergency_number"] ?? "");

$emergency_relation =
    trim($_POST["emergency_relation"] ?? "");

if (!empty($emergency_name)) {

    $stmt = mysqli_prepare(
        $conn,
        "
        INSERT INTO resident_emergency_contacts (
            resident_id,
            contact_name,
            contact_number,
            relationship
        )
        VALUES (?, ?, ?, ?)
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $resident_id,
        $emergency_name,
        $emergency_number,
        $emergency_relation
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   DISABILITIES
========================= */

$disabilities =
    $_POST["disability_type"] ?? [];

$remarks =
    trim($_POST["remarks"] ?? "");

foreach ($disabilities as $type) {

    $stmt = mysqli_prepare(
        $conn,
        "
        INSERT INTO resident_disabilities (
            resident_id,
            disability_type,
            remarks
        )
        VALUES (?, ?, ?)
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $resident_id,
        $type,
        $remarks
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   FAMILY MEMBERS
========================= */

$family = [

    [
        "Father",
        trim($_POST["father_name"] ?? "")
    ],

    [
        "Mother",
        trim($_POST["mother_name"] ?? "")
    ],

    [
        "Spouse",
        trim($_POST["spouse_name"] ?? "")
    ],

    [
        trim($_POST["child_relation"] ?? ""),
        trim($_POST["guardian_name"] ?? "")
    ]
];

foreach ($family as $member) {

    if (empty($member[1])) {
        continue;
    }

    $stmt = mysqli_prepare(
        $conn,
        "
        INSERT INTO resident_family_members (
            resident_id,
            member_role,
            member_name
        )
        VALUES (?, ?, ?)
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $resident_id,
        $member[0],
        $member[1]
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   AUDIT LOG
========================= */

auditLog(
    $conn,
    "CREATE",
    "Registration",
    $resident_id,
    "Registered new resident"
);

$_SESSION["reg_success"] =
    "Resident registered successfully.";

header("Location: ../registration.php");

exit();
?>