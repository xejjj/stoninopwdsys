<?php
session_start();
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resident_id = intval($_POST["resident_id"]);
    $new_issue_date = $_POST["new_date_issued"];
    $new_expiration_date = $_POST["new_expiration_date"];

    if (!empty($resident_id) && !empty($new_issue_date) && !empty($new_expiration_date)) {
        
        $safe_issue = mysqli_real_escape_string($conn, $new_issue_date);
        $safe_expiration = mysqli_real_escape_string($conn, $new_expiration_date);

        // Update dates and change expired status back to active
        $sql = "UPDATE residents 
                SET idissue_date = '$safe_issue', 
                    idexpiration_date = '$safe_expiration',
                    record_status = 'active'
                WHERE ID = $resident_id";

        if (mysqli_query($conn, $sql)) {
            // Success: send user back to previous page
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                header("Location: ../resident.php");
            }
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Missing required date fields.";
    }
} else {
    header("Location: ../resident.php");
    exit();
}
?>