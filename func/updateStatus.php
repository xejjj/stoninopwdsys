<?php
session_start();
require_once("db.php");
require_once("audit.php");

// ── Auth check ────────────────────────────────────────
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $allowed_statuses = ['Active', 'Rejected', 'Under Review', 'Needs Correction'];
    $main_table = "residents";

    if (in_array($status, $allowed_statuses)) {

        if ($status === 'Needs Correction') {
            $reason = isset($_GET['reason']) ? trim($_GET['reason']) : 'No reason provided.';

            $stmt = $conn->prepare("UPDATE residents SET status = 'Needs Correction', correction_remarks = ? WHERE ID = ?");
            $stmt->bind_param("si", $reason, $id);

            if ($stmt->execute()) {
                $del = $conn->prepare("DELETE FROM rejected WHERE ID = ?");
                $del->bind_param("i", $id);
                $del->execute();

                auditLog($conn, "UPDATE", "Residents", $id, "Status set to Needs Correction");
                header("Location: ../review.php?filter=Needs%20Correction&success=moved");
                exit();
            }

        } elseif ($status === 'Active') {
            $stmt = $conn->prepare("UPDATE residents SET status = 'Active' WHERE ID = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $del = $conn->prepare("DELETE FROM rejected WHERE ID = ?");
                $del->bind_param("i", $id);
                $del->execute();

                auditLog($conn, "UPDATE", "Residents", $id, "Status set to Active (approved)");
                header("Location: ../review.php?filter=All&success=approved");
                exit();
            }

        } elseif ($status === 'Rejected') {
            $upd = $conn->prepare("UPDATE residents SET status = 'Rejected' WHERE ID = ?");
            $upd->bind_param("i", $id);
            $upd->execute();

            $copy = $conn->prepare("INSERT IGNORE INTO rejected SELECT * FROM residents WHERE ID = ?");
            $copy->bind_param("i", $id);

            if ($copy->execute()) {
                $del = $conn->prepare("DELETE FROM residents WHERE ID = ?");
                $del->bind_param("i", $id);
                $del->execute();

                auditLog($conn, "DELETE", "Residents", $id, "Resident rejected and moved to rejected table");
                header("Location: ../review.php?filter=Rejected&success=rejected");
                exit();
            }
        }
    }
}

$conn->close();
?>