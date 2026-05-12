<?php
require_once("db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM rejected WHERE ID = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../review.php?filter=Rejected&success=deleted");
        exit();
    } else {
        die("Error deleting record: " . $conn->error);
    }
    
    $stmt->close();
} else {
    die("Missing ID parameter.");
}

$conn->close();
?>