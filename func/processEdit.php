<?php

session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_POST["resident_id"] ?? 0);

if ($id <= 0) {
    header("Location: ../resident.php");
    exit();
}

/* =========================
   MAIN DATA
========================= */

$first_name   = trim($_POST["first_name"] ?? "");
$middle_name  = trim($_POST["middle_name"] ?? "");
$last_name    = trim($_POST["last_name"] ?? "");
$civil_status = trim($_POST["civil_status"] ?? "");
$birthdate    = trim($_POST["dob"] ?? "");
$birthplace   = trim($_POST["pob"] ?? "");
$sex          = strtolower(trim($_POST["sex"] ?? ""));
$address      = trim($_POST["address"] ?? "");

$resident_type =
    !empty($_POST["guardian_name"])
    ? "CWD"
    : "PWD";

/* =========================
   CONTACTS
========================= */

$contact_number =
    trim($_POST["contact_number"] ?? "");

$socials =
    trim($_POST["account_name"] ?? "");

/* =========================
   EMERGENCY CONTACT
========================= */

$emergency_name =
    trim($_POST["emergency_name"] ?? "");

$emergency_number =
    trim($_POST["emergency_number"] ?? "");

$emergency_relation =
    trim($_POST["emergency_relation"] ?? "");

/* =========================
   DISABILITY
========================= */

$disabilities =
    $_POST["disability_type"] ?? [];

$remarks =
    trim($_POST["remarks"] ?? "");

/* =========================
   FAMILY
========================= */

$father_name =
    trim($_POST["father_name"] ?? "");

$mother_name =
    trim($_POST["mother_name"] ?? "");

$spouse_name =
    trim($_POST["spouse_name"] ?? "");

$guardian_name =
    trim($_POST["guardian_name"] ?? "");

$guardian_rel =
    trim($_POST["child_relation"] ?? "");

    $guardian_number =
    trim($_POST["guardian_number"] ?? "");

/* =========================
   ID
========================= */

$pwdid_num =
    trim($_POST["pwd_id"] ?? "");

$control_num =
    trim($_POST["control_id"] ?? "");

$idissue_date =
    trim($_POST["date_issued"] ?? "");

$idexpiration_date =
    trim($_POST["expiration_date"] ?? "");

/* =========================
   CURRENT FILES
========================= */

$current =
    mysqli_query(
        $conn,
        "SELECT profile, med_cert
         FROM residents
         WHERE ID = $id"
    );

$current_data =
    mysqli_fetch_assoc($current);

$profile =
    $current_data["profile"] ?? "";

$med_cert =
    $current_data["med_cert"] ?? "";

/* =========================
   PROFILE UPLOAD
========================= */

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

    if (
        move_uploaded_file(
            $_FILES["profile_pic"]["tmp_name"],
            $target
        )
    ) {
        $profile =
            "uploads/profiles/" .
            $safe_name;
    }
}

/* =========================
   MED CERT UPLOAD
========================= */

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

    if (
        move_uploaded_file(
            $_FILES["med_cert"]["tmp_name"],
            $target
        )
    ) {
        $med_cert =
            "uploads/medical_certificates/" .
            $safe_name;
    }
}

/* =========================
   UPDATE RESIDENT
========================= */

$sql = "
UPDATE residents SET

    first_name = ?,
    middle_name = ?,
    last_name = ?,
    civil_status = ?,
    birthdate = ?,
    birthplace = ?,
    sex = ?,
    address = ?,
    resident_type = ?,

    pwdid_num = ?,
    control_num = ?,
    idissue_date = ?,
    idexpiration_date = ?,

    profile = ?,
    med_cert = ?

WHERE ID = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssssssi",
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
    $id
);

if (!mysqli_stmt_execute($stmt)) {

    $_SESSION["edit_error"] =
        mysqli_stmt_error($stmt);

    header("Location: ../editResident.php?id=$id");
    exit();
}

/* =========================
   CONTACTS
========================= */

mysqli_query(
    $conn,
    "DELETE FROM resident_contacts
     WHERE resident_id = $id"
);

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO resident_contacts (
        resident_id,
        name,
        contact_num,
        socials
    ) VALUES (?, ?, ?, ?)"
);

$contact_name = "Primary Contact";

mysqli_stmt_bind_param(
    $stmt,
    "isss",
    $id,
    $contact_name,
    $contact_number,
    $socials
);

mysqli_stmt_execute($stmt);

/* =========================
   EMERGENCY CONTACT
========================= */

mysqli_query(
    $conn,
    "
    DELETE FROM resident_emergency_contacts
    WHERE resident_id = $id
    "
);

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
    $id,
    $emergency_name,
    $emergency_number,
    $emergency_relation
);

mysqli_stmt_execute($stmt);

/* =========================
   DISABILITIES
========================= */

mysqli_query(
    $conn,
    "
    DELETE FROM resident_disabilities
    WHERE resident_id = $id
    "
);

foreach ($disabilities as $type) {

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
        $id,
        $type,
        $remarks
    );

    mysqli_stmt_execute($stmt);
}

/* =========================
   FAMILY MEMBERS
========================= */

mysqli_query(
    $conn,
    "
    DELETE FROM resident_family_members
    WHERE resident_id = $id
    "
);

$family = [
    ["Father", $father_name, ""],
    ["Mother", $mother_name, ""],
    ["Spouse", $spouse_name, ""],
    [$guardian_rel ?: "Guardian", $guardian_name, $guardian_number]
];

foreach ($family as $member) {
    if (empty($member[1])) {
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
        $id,
        $member[1],
        $member[0],
        $member[2]
    );

    mysqli_stmt_execute($stmt);
}
/* =========================
   AUDIT
========================= */

auditLog(
    $conn,
    "UPDATE",
    "Residents",
    $id,
    "Updated resident profile"
);

$_SESSION["edit_success"] = true;

header("Location: ../editResident.php?id=$id");

exit();
?>