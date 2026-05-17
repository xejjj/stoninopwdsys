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

/* DESTROY SESSION */

$_SESSION = [];

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/* FORCE NO CACHE */

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: ../index.php");
exit();
?>