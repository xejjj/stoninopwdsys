<?php
// func/updateStatus.php
require_once("db.php"); 

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status']; 
    
    $allowed_statuses = ['Active', 'Rejected', 'Under Review', 'Needs Correction'];
    $main_table = "residents"; 
    
    if (in_array($status, $allowed_statuses)) {
        
        if ($status === 'Needs Correction') {
            $reason = isset($_GET['reason']) ? trim($_GET['reason']) : 'No reason provided.';
            
            // 1. Update the resident in the main table
            $stmt = $conn->prepare("UPDATE $main_table SET status = 'Needs Correction', correction_remarks = ? WHERE ID = ?");
            $stmt->bind_param("si", $reason, $id);
            
            if ($stmt->execute()) {
                // 2. CLEAN UP: Remove from rejected just in case
                $conn->query("DELETE FROM rejected WHERE ID = $id");
                
                header("Location: ../review.php?filter=Needs Correction&success=moved");
                exit();
            }

        } elseif ($status === 'Active') {
            // 1. Set status to Active
            $stmt = $conn->prepare("UPDATE $main_table SET status = 'Active' WHERE ID = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                // 2. CLEAN UP
                $conn->query("DELETE FROM rejected WHERE ID = $id");
                
                header("Location: ../review.php?filter=All&success=approved");
                exit();
            }

        } elseif ($status === 'Rejected') {
            // NEW: Officially change status to Rejected in the main table first
            // This ensures that when it is copied, the status isn't still "Needs Correction"
            $conn->query("UPDATE $main_table SET status = 'Rejected' WHERE ID = $id");

            // 1. Copy the updated data (now marked as Rejected) to the backup table
            $copy = $conn->prepare("INSERT IGNORE INTO rejected SELECT * FROM $main_table WHERE ID = ?");
            $copy->bind_param("i", $id);
            
            if ($copy->execute()) {
                // 2. REMOVE from the main residents table
                $del = $conn->prepare("DELETE FROM $main_table WHERE ID = ?");
                $del->bind_param("i", $id);
                $del->execute();
                
                header("Location: ../review.php?filter=Rejected&success=rejected");
                exit();
            }
        }
    }
}
$conn->close();
?>