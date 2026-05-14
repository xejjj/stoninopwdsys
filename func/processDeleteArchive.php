<?php
session_start();

require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../archive.php");
    exit();
}

$id = intval($_POST["archive_id"] ?? 0);
mysqli_begin_transaction($conn);

if ($id <= 0) {
    header("Location: ../archive.php");
    exit();
}

/* FETCH FILES */

$fetch = mysqli_prepare(
    $conn,
    "
    SELECT first_name, last_name,
           profile, med_cert
    FROM residents
    WHERE ID = ?
    "
);

mysqli_stmt_bind_param(
    $fetch,
    "i",
    $id
);

mysqli_stmt_execute($fetch);

$result =
    mysqli_stmt_get_result($fetch);

$r =
    mysqli_fetch_assoc($result);

if (!$r) {

    $_SESSION["arch_error"] =
        "Resident not found.";

    header("Location: ../archive.php");
    exit();
}


/* DELETE MAIN */

$del = mysqli_prepare(
    $conn,
    "
    DELETE FROM residents
    WHERE ID = ?
    "
);

mysqli_stmt_bind_param(
    $del,
    "i",
    $id
);

if (mysqli_stmt_execute($del)) {
    mysqli_commit($conn);

    if (!empty($r["profile"])) {

        $profile =
            "../" . $r["profile"];

        if (file_exists($profile)) {
            unlink($profile);
        }
    }

    if (!empty($r["med_cert"])) {

        $med =
            "../" . $r["med_cert"];

        if (file_exists($med)) {
            unlink($med);
        }
    }

    auditLog(
        $conn,
        "DELETE",
        "Archive",
        $id,
        "Deleted archived resident permanently"
    );

    $_SESSION["arch_success"] =
        "Resident permanently deleted.";

} else {

    $_SESSION["arch_error"] =
        "Failed to delete resident.";
}

if (mysqli_errno($conn)) {
    mysqli_rollback($conn);
}
header("Location: ../archive.php");

exit();
?>