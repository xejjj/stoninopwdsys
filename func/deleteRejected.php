<?php
session_start();
require_once("db.php");
require_once("audit.php");

// ── Auth check ────────────────────────────────────────
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch name before deleting for audit log
    $fetch = $conn->prepare("SELECT first_name, last_name FROM residents WHERE ID = ?");
    $fetch->bind_param("i", $id);
    $fetch->execute();
    $result = $fetch->get_result();
    $r = $result->fetch_assoc();

    $stmt = $conn->prepare("DELETE FROM rejected WHERE ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($r) {
            auditLog($conn, "DELETE", "Rejected", $id, "Deleted rejected record: " . $r["first_name"] . " " . $r["last_name"]);
        }
        header("Location: ../review.php?filter=Rejected&success=deleted");
        exit();
    } else {
        die("Error deleting record: " . $conn->error);
    }

    $stmt->close();
} else {
    header("Location: ../review.php");
    exit();
}

$conn->close();
?>