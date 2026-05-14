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

if (mysqli_connect_errno()) {

    die(
        "Failed to connect to MySQL: " .
        mysqli_connect_error()
    );
}

/* =========================
   UPDATE AGES
========================= */

mysqli_query(
    $conn,
    "UPDATE residents
     SET age = TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
     )
     WHERE birthdate IS NOT NULL
     AND birthdate != ''"
);

mysqli_query(
    $conn,
    "UPDATE archive
     SET age = TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
     )
     WHERE birthdate IS NOT NULL
     AND birthdate != ''"
);


/* =========================
   AUTO UPDATE RESIDENT TYPE
========================= */

/* 1 TO 17 = CWD */

mysqli_query(
    $conn,
    "UPDATE residents
     SET resident_type = 'CWD'
     WHERE age >= 1
     AND age < 18"
);

/* 0 OR 18+ = PWD */

mysqli_query(
    $conn,
    "UPDATE residents
     SET resident_type = 'PWD'
     WHERE age = 0
     OR age >= 18"
);

/* ARCHIVE TABLE */

mysqli_query(
    $conn,
    "UPDATE archive
     SET resident_type = 'CWD'
     WHERE age >= 1
     AND age < 18"
);

mysqli_query(
    $conn,
    "UPDATE archive
     SET resident_type = 'PWD'
     WHERE age = 0
     OR age >= 18"
);

mysqli_query(
    $conn,
    "UPDATE residents
     SET status = 'Expired'
     WHERE idexpiration_date IS NOT NULL
     AND idexpiration_date != ''
     AND DATE(idexpiration_date) < CURDATE()
     AND status != 'Expired'"
);
?>