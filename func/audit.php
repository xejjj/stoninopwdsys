<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function auditLog(
    $conn,
    $action,
    $module,
    $resident_id,
    $description
) {

    $admin_id =
        $_SESSION["admin_id"] ?? null;

    $admin_name =
        $_SESSION["admin_name"] ?? "Unknown Admin";

    $role =
        $_SESSION["role"] ?? "Unknown";

    $sql = "
    INSERT INTO audit_logs (
        admin_id,
        admin_name,
        role,
        resident_id,
        action,
        module,
        description
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ississs",
        $admin_id,
        $admin_name,
        $role,
        $resident_id,
        $action,
        $module,
        $description
    );

    mysqli_stmt_execute($stmt);
}
?>