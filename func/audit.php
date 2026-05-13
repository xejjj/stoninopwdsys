<?php
function auditLog($conn, $action, $module, $record_id, $description) {
    $admin_id   = $_SESSION["admin_id"] ?? null;
    $admin_name = $_SESSION["admin_name"] ?? "Unknown User";
    $role       = $_SESSION["role"] ?? "Unknown";

    $sql = "INSERT INTO audit_logs (
                admin_id, admin_name, role, action, module, record_id, description
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "issssis",
        $admin_id,
        $admin_name,
        $role,
        $action,
        $module,
        $record_id,
        $description
    );

    return mysqli_stmt_execute($stmt);
}
?>