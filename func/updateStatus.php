<?php
session_start();
require_once("db.php");
require_once("audit.php");

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["status"])) {
    header("Location: ../review.php");
    exit();
}

$id = intval($_GET["id"]);
$status = trim($_GET["status"]);

$allowed_statuses = [
    "Active",
    "Rejected",
    "Under Review",
    "Needs Correction"
];

if ($id <= 0 || !in_array($status, $allowed_statuses)) {
    header("Location: ../review.php");
    exit();
}

if ($status === "Needs Correction") {
    $reason = trim($_GET["reason"] ?? "No reason provided.");

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE residents
         SET application_status = 'needs correction',
             record_status = 'active',
             correction_remarks = ?
         WHERE ID = ?"
    );

    mysqli_stmt_bind_param($stmt, "si", $reason, $id);

    if (mysqli_stmt_execute($stmt)) {
        auditLog(
            $conn,
            "UPDATE",
            "Review",
            $id,
            "Status set to Needs Correction. Reason: " . $reason
        );

        header("Location: ../review.php?filter=Needs%20Correction&success=moved");
        exit();
    }
}

if ($status === "Active") {
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE residents
         SET application_status = 'approved',
             record_status = 'active'
         WHERE ID = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        auditLog(
            $conn,
            "APPROVE",
            "Review",
            $id,
            "Resident approved"
        );

        header("Location: ../review.php?filter=All&success=approved");
        exit();
    }
}

if ($status === "Rejected") {
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE residents
         SET application_status = 'rejected',
             record_status = 'active'
         WHERE ID = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        auditLog(
            $conn,
            "REJECT",
            "Review",
            $id,
            "Resident rejected"
        );

        header("Location: ../review.php?filter=Rejected&success=rejected");
        exit();
    }
}

if ($status === "Under Review") {
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE residents
         SET application_status = 'under review',
             record_status = 'active'
         WHERE ID = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        auditLog(
            $conn,
            "UPDATE",
            "Review",
            $id,
            "Status set to Under Review"
        );

        header("Location: ../review.php?filter=Under%20Review&success=updated");
        exit();
    }
}

$_SESSION["review_error"] = "Failed to update status.";
header("Location: ../review.php");
exit();
?>