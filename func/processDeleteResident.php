<?php

session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: ../review.php");
    exit();
}

$fetch = mysqli_prepare(
    $conn,
    "SELECT first_name, last_name
     FROM residents
     WHERE ID = ?"
);

mysqli_stmt_bind_param($fetch, "i", $id);

mysqli_stmt_execute($fetch);

$result =
    mysqli_stmt_get_result($fetch);

$resident =
    mysqli_fetch_assoc($result);

if (!$resident) {
    header("Location: ../review.php");
    exit();
}

/* DELETE CHILD RECORDS */

mysqli_query(
    $conn,
    "DELETE FROM resident_contacts
     WHERE resident_id = $id"
);

mysqli_query(
    $conn,
    "DELETE FROM resident_emergency_contacts
     WHERE resident_id = $id"
);

mysqli_query(
    $conn,
    "DELETE FROM resident_disabilities
     WHERE resident_id = $id"
);

mysqli_query(
    $conn,
    "DELETE FROM resident_family_members
     WHERE resident_id = $id"
);

/* DELETE MAIN RECORD */

$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM residents
     WHERE ID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    auditLog(
        $conn,
        "DELETE",
        "Residents",
        $id,
        "Deleted resident permanently"
    );
}

header("Location: ../review.php?filter=Rejected");

exit();
?>