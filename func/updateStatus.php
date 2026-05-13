<?php
require_once("db.php"); 

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status']; 
    
    $allowed_statuses = ['Active', 'Rejected', 'Under Review', 'Needs Correction'];
    $main_table = "residents"; 
    
    if (in_array($status, $allowed_statuses)) {
        
        if ($status === 'Needs Correction') {
            $reason = isset($_GET['reason']) ? trim($_GET['reason']) : 'No reason provided.';
            
            $stmt = $conn->prepare("UPDATE $main_table SET status = 'Needs Correction', correction_remarks = ? WHERE ID = ?");
            $stmt->bind_param("si", $reason, $id);
            
            if ($stmt->execute()) {
                $conn->query("DELETE FROM rejected WHERE ID = $id");
                
                header("Location: ../review.php?filter=Needs%20Correction&success=moved");
                exit();
            }

        } elseif ($status === 'Active') {
            $stmt = $conn->prepare("UPDATE $main_table SET status = 'Active' WHERE ID = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $conn->query("DELETE FROM rejected WHERE ID = $id");
                
                header("Location: ../review.php?filter=All&success=approved");
                exit();
            }

        } elseif ($status === 'Rejected') {
            $conn->query("UPDATE $main_table SET status = 'Rejected' WHERE ID = $id");

            $copy = $conn->prepare("INSERT IGNORE INTO rejected SELECT * FROM $main_table WHERE ID = ?");
            $copy->bind_param("i", $id);
            
            if ($copy->execute()) {
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