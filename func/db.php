<?php

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "stoninopwdsysdb";

$conn = mysqli_connect(
    $db_server,
    $db_user,
    $db_pass,
    $db_name
);

if (!$conn) {
    die(
        "Failed to connect to MySQL: " .
        mysqli_connect_error()
    );
}

/* =========================
   AUTO UPDATE RESIDENT TYPE
========================= */

/* BELOW 18 = CWD */

mysqli_query(
    $conn,
    "UPDATE residents
     SET resident_type = 'CWD'
     WHERE TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
     ) < 18"
);

/* 18+ = PWD */

mysqli_query(
    $conn,
    "UPDATE residents
     SET resident_type = 'PWD'
     WHERE TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
     ) >= 18"
);

/* =========================
   AUTO UPDATE RECORD STATUS
========================= */

mysqli_query(
    $conn,
    "UPDATE residents
     SET record_status = 'expired'
     WHERE idexpiration_date IS NOT NULL
     AND idexpiration_date != ''
     AND DATE(idexpiration_date) < CURDATE()
     AND record_status != 'archived'"
);

?>