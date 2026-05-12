<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ── Personal Information ──────────────────────────────
    $first_name        = trim($_POST["first_name"]);
    $middle_name       = trim($_POST["middle_name"]);
    $last_name         = trim($_POST["last_name"]);
    $civil_status      = trim($_POST["civil_status"]);
    $birthdate         = trim($_POST["dob"]);
    $birthplace        = trim($_POST["pob"]);
    $age               = intval($_POST["age"]);
    $sex               = strtolower(trim($_POST["sex"]));

    // ── Contact & Address ─────────────────────────────────
    $contact_num        = trim($_POST["contact_number"]);
    $emergency_cont     = trim($_POST["emergency_name"]);
    $emergency_cont_num = trim($_POST["emergency_number"]);
    $emergency_cont_rel = trim($_POST["emergency_relation"]);
    $socials            = trim($_POST["account_name"]);
    $address            = trim($_POST["address"]);

    // ── Disability ────────────────────────────────────────
    $disablity_type = isset($_POST["disablity_type"])
        ? implode(", ", $_POST["disablity_type"])
        : "";

    // ── Resident Type ─────────────────────────────────────
    $guardian_name   = trim($_POST["guardian_name"]);
    $guardian_number = trim($_POST["guardian_number"]);
    $guardian_rel    = trim($_POST["child_relation"]);
    $resident_type   = (!empty($guardian_name)) ? "CWD" : "PWD";

    // ── Family Information ────────────────────────────────
    $father_name = trim($_POST["father_name"]);
    $mother_name = trim($_POST["mother_name"]);
    $spouse_name = trim($_POST["spouse_name"]);

    // ── ID Registration ───────────────────────────────────
    $pwdid_num         = trim($_POST["pwd_id"]);
    $control_num       = trim($_POST["control_id"]);
    $idissue_date      = trim($_POST["date_issued"]);
    $idexpiration_date = trim($_POST["expiration_date"]);

    // ── Basic Validation ──────────────────────────────────
    if (empty($first_name) || empty($last_name) || empty($birthdate) || empty($sex)) {
        $_SESSION["reg_error"] = "Please fill in all required fields.";
        $_SESSION['form_data'] = $_POST; // Save input data
        header("Location: ../selfregistration.php");
        exit();
    }

    if (empty($disablity_type)) {
        $_SESSION["reg_error"] = "Please select at least one disability type.";
        $_SESSION['form_data'] = $_POST; // Save input data
        header("Location: ../selfregistration.php");
        exit();
    }

    // ── Profile Picture Upload ────────────────────────────
    $profile = "";
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] === 0) {
        $upload_dir = "../uploads/profiles/"; // Adjusted path if processSelfReg is inside /func

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $ext       = pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $safe_name = uniqid("profile_", true) . "." . $ext;
        $target    = $upload_dir . $safe_name;

        $allowed = ["jpg", "jpeg", "png", "gif", "webp"];
        if (!in_array(strtolower($ext), $allowed)) {
            $_SESSION["reg_error"] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
            $_SESSION['form_data'] = $_POST; // Save input data
            header("Location: ../selfregistration.php");
            exit();
        }

        if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target)) {
            $_SESSION["reg_error"] = "Failed to upload profile picture.";
            $_SESSION['form_data'] = $_POST; // Save input data
            header("Location: ../selfregistration.php");
            exit();
        }

        $profile = $target;
    }

    // ── Insert into DB ────────────────────────────────────
    $sql = "INSERT INTO residents (
                first_name, middle_name, last_name, civil_status,
                birthdate, age, birthplace, sex,
                address, contact_num,
                emergency_cont, emergency_cont_num, emergency_cont_rel,
                socials, disablity_type, resident_type,
                guardian_name, guardian_cont_num, guardian_rel,
                father_name, mother_name, spouse_name,
                pwdid_num, control_num, idissue_date, idexpiration_date,
                profile
            ) VALUES (
                ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?, ?,
                ?
            )";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $_SESSION["reg_error"] = "Database error: " . mysqli_error($conn);
        $_SESSION['form_data'] = $_POST; // Save input data
        header("Location: ../selfregistration.php");
        exit();
    }

    //        1234567890123456789012345678
    //        sssssis = age is i, rest s = 27 total
    mysqli_stmt_bind_param($stmt, "sssssisssssssssssssssssssss",
        $first_name,         // 1  s
        $middle_name,        // 2  s
        $last_name,          // 3  s
        $civil_status,       // 4  s
        $birthdate,          // 5  s
        $age,                // 6  i ← integer
        $birthplace,         // 7  s
        $sex,                // 8  s
        $address,            // 9  s
        $contact_num,        // 10 s
        $emergency_cont,     // 11 s
        $emergency_cont_num, // 12 s
        $emergency_cont_rel, // 13 s
        $socials,            // 14 s
        $disablity_type,     // 15 s
        $resident_type,      // 16 s
        $guardian_name,      // 17 s
        $guardian_number,    // 18 s
        $guardian_rel,       // 19 s
        $father_name,        // 20 s
        $mother_name,        // 21 s
        $spouse_name,        // 22 s
        $pwdid_num,          // 23 s
        $control_num,        // 24 s
        $idissue_date,       // 25 s
        $idexpiration_date,  // 26 s
        $profile             // 27 s
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["reg_success"] = "Registration submitted successfully! Please wait 3-5 business days for processing.";
        header("Location: ../selfregistration.php");
        exit();
    } else {
        $_SESSION["reg_error"] = "Failed to submit: " . mysqli_stmt_error($stmt);
        $_SESSION['form_data'] = $_POST; // Save input data
        header("Location: ../selfregistration.php");
        exit();
    }
}
?>