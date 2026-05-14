<?php
session_start();

require_once("db.php");
require_once("audit.php");

if (isset($_SESSION["admin_id"])) {

    auditLog(
        $conn,
        "LOGOUT",
        "Authentication",
        null,
        ($_SESSION["admin_name"] ?? "Unknown Admin") . " logged out"
    );
}

session_unset();
session_destroy();

header("Location: ../login.php");
exit();
?>