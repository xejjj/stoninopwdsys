<?php

session_start();

require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../selfregistration.php");
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

$guardian_name   = trim($_POST["guardian_name"] ?? "");
$guardian_number = trim($_POST["guardian_number"] ?? "");
$guardian_rel    = trim($_POST["child_relation"] ?? "");

$resident_type =
    !empty($guardian_name)
    ? "CWD"
    : "PWD";

$application_status = "under review";
$record_status      = "active";

/* =========================
   VALIDATION
========================= */

if (
    empty($first_name) ||
    empty($last_name) ||
    empty($birthdate) ||
    empty($sex)
) {

    $_SESSION["reg_error"] =
        "Please fill in all required fields.";

    $_SESSION["form_data"] = $_POST;

    header("Location: ../selfregistration.php");
    exit();
}

$disabilities =
    $_POST["disability_type"] ?? [];

if (empty($disabilities)) {

    $_SESSION["reg_error"] =
        "Please select at least one disability type.";

    $_SESSION["form_data"] = $_POST;

    header("Location: ../selfregistration.php");
    exit();
}

/* =========================
   PROFILE UPLOAD
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

    $allowed =
        ["jpg", "jpeg", "png", "gif", "webp"];

    if (!in_array($ext, $allowed)) {

        $_SESSION["reg_error"] =
            "Invalid profile picture.";

        $_SESSION["form_data"] = $_POST;

        header("Location: ../selfregistration.php");
        exit();
    }

    $base_name =
        strtolower(
            preg_replace(
                "/[^a-zA-Z0-9]+/",
                "_",
                $first_name . "_" . $last_name
            )
        );

    $safe_name =
        $base_name .
        "_profile_" .
        time() .
        "." .
        $ext;

    $target =
        $upload_dir .
        $safe_name;

    if (!move_uploaded_file(
        $_FILES["profile_pic"]["tmp_name"],
        $target
    )) {

        $_SESSION["reg_error"] =
            "Failed to upload profile picture.";

        $_SESSION["form_data"] = $_POST;

        header("Location: ../selfregistration.php");
        exit();
    }

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

    $allowed =
        ["pdf", "jpg", "jpeg", "png"];

    if (!in_array($ext, $allowed)) {

        $_SESSION["reg_error"] =
            "Invalid medical certificate.";

        $_SESSION["form_data"] = $_POST;

        header("Location: ../selfregistration.php");
        exit();
    }

    $base_name =
        strtolower(
            preg_replace(
                "/[^a-zA-Z0-9]+/",
                "_",
                $first_name . "_" . $last_name
            )
        );

    $safe_name =
        $base_name .
        "_medcert_" .
        time() .
        "." .
        $ext;

    $target =
        $upload_dir .
        $safe_name;

    if (!move_uploaded_file(
        $_FILES["med_cert"]["tmp_name"],
        $target
    )) {

        $_SESSION["reg_error"] =
            "Failed to upload medical certificate.";

        $_SESSION["form_data"] = $_POST;

        header("Location: ../selfregistration.php");
        exit();
    }

    $med_cert =
        "uploads/medical_certificates/" .
        $safe_name;
}

/* =========================
   INSERT RESIDENT
========================= */

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO residents (
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
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )"
);

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
        mysqli_stmt_error($stmt);

    $_SESSION["form_data"] = $_POST;

    header("Location: ../selfregistration.php");
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

    $contact_name =
        "Primary Contact";

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO resident_contacts (
            resident_id,
            name,
            contact_num,
            socials
        ) VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $resident_id,
        $contact_name,
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
        "INSERT INTO resident_emergency_contacts (
            resident_id,
            name,
            contact_num,
            relationship
        ) VALUES (?, ?, ?, ?)"
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

$remarks =
    trim($_POST["remarks"] ?? "");

foreach ($disabilities as $type) {

    $type = trim($type);

    if (empty($type)) {
        continue;
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO resident_disabilities (
            resident_id,
            disability_type,
            notes
        ) VALUES (?, ?, ?)"
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

$father_name =
    trim($_POST["father_name"] ?? "");

$mother_name =
    trim($_POST["mother_name"] ?? "");

$spouse_name =
    trim($_POST["spouse_name"] ?? "");

$family_members = [
    ["Father", $father_name, ""],
    ["Mother", $mother_name, ""],
    ["Spouse", $spouse_name, ""],
];

if (!empty($guardian_name)) {

    $family_members[] = [
        !empty($guardian_rel)
            ? $guardian_rel
            : "Guardian",

        $guardian_name,
        $guardian_number
    ];
}

foreach ($family_members as $member) {

    $relationship =
        $member[0];

    $name =
        $member[1];

    $family_contact_num =
        $member[2];

    if (empty($name)) {
        continue;
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO resident_family_members (
            resident_id,
            name,
            relationship,
            contact_num
        ) VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $resident_id,
        $name,
        $relationship,
        $family_contact_num
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   SUCCESS
========================= */

$_SESSION["reg_success"] =
    "Registration submitted successfully! Please wait 3-5 business days for processing.";

header("Location: ../selfregistration.php");
exit();

?>