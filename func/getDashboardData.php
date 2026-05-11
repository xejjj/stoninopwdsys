<?php
session_start();
require_once("db.php");

// ── Total count ───────────────────────────────────────
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM residents");
$total_row    = mysqli_fetch_assoc($total_result);
$total        = $total_row["total"];

// ── Age brackets ──────────────────────────────────────
$minors_result  = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE age BETWEEN 0 AND 17");
$minors_count   = mysqli_fetch_assoc($minors_result)["cnt"];

$adults_result  = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE age BETWEEN 18 AND 59");
$adults_count   = mysqli_fetch_assoc($adults_result)["cnt"];

$seniors_result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE age >= 60");
$seniors_count  = mysqli_fetch_assoc($seniors_result)["cnt"];

// ── Bar widths (% of total) ───────────────────────────
$minors_pct  = $total > 0 ? max(4, round(($minors_count  / $total) * 100)) : 0;
$adults_pct  = $total > 0 ? max(4, round(($adults_count  / $total) * 100)) : 0;
$seniors_pct = $total > 0 ? max(4, round(($seniors_count / $total) * 100)) : 0;

// ── Registration Status (Active / Pending / Expired) ──
// Assumes you have a `status` column — adjust values to match your DB enum
$active_result  = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE status = 'Active'");
$active_count   = mysqli_fetch_assoc($active_result)["cnt"];

$pending_result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE status = 'Pending'");
$pending_count  = mysqli_fetch_assoc($pending_result)["cnt"];

$expired_result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM residents WHERE status = 'Expired'");
$expired_count  = mysqli_fetch_assoc($expired_result)["cnt"];

// ── Pie chart SVG arc helper ──────────────────────────
function pieSlice($cx, $cy, $r, $startDeg, $endDeg) {
    $start = deg2rad($startDeg - 90);
    $end   = deg2rad($endDeg   - 90);
    $large = ($endDeg - $startDeg) > 180 ? 1 : 0;
    $x1 = round($cx + $r * cos($start), 2);
    $y1 = round($cy + $r * sin($start), 2);
    $x2 = round($cx + $r * cos($end),   2);
    $y2 = round($cy + $r * sin($end),   2);
    return "M{$cx},{$cy} L{$x1},{$y1} A{$r},{$r} 0 {$large},1 {$x2},{$y2} Z";
}

// Calculate pie degrees for 3 slices
$active_deg  = $total > 0 ? ($active_count  / $total) * 360 : 0;
$pending_deg = $total > 0 ? ($pending_count / $total) * 360 : 0;
$expired_deg = $total > 0 ? ($expired_count / $total) * 360 : 0;

$active_path  = $total > 0 ? pieSlice(90, 90, 80, 0, $active_deg) : "";
$pending_path = $total > 0 ? pieSlice(90, 90, 80, $active_deg, $active_deg + $pending_deg) : "";
$expired_path = $total > 0 ? pieSlice(90, 90, 80, $active_deg + $pending_deg, 360) : "";

// ── Directory rows ────────────────────────────────────
$residents_result = mysqli_query($conn, "SELECT * FROM residents ORDER BY last_name ASC");

// ── Disability badge CSS class map ────────────────────
function badgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"        => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
    ];
    $key = strtolower(trim(explode(",", $type)[0]));
    return $map[$key] ?? "badge-physical";
}
?>