<?php
require_once("db.php");



/* =========================
   TOTAL ACTIVE RESIDENTS
========================= */

$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM residents
    WHERE record_status = 'active'
");

$total = mysqli_fetch_assoc($total_result)["total"] ?? 0;

/* =========================
   AGE BRACKETS
========================= */

$minors_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE record_status = 'active'
    AND TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
    ) BETWEEN 0 AND 17
");

$minors_count = mysqli_fetch_assoc($minors_result)["cnt"] ?? 0;

$adults_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE record_status = 'active'
    AND TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
    ) BETWEEN 18 AND 59
");

$adults_count = mysqli_fetch_assoc($adults_result)["cnt"] ?? 0;

$seniors_result = mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE record_status = 'active'
    AND TIMESTAMPDIFF(
        YEAR,
        birthdate,
        CURDATE()
    ) >= 60
");

$seniors_count = mysqli_fetch_assoc($seniors_result)["cnt"] ?? 0;

$minors_pct  = $total > 0 ? max(4, round(($minors_count / $total) * 100)) : 0;
$adults_pct  = $total > 0 ? max(4, round(($adults_count / $total) * 100)) : 0;
$seniors_pct = $total > 0 ? max(4, round(($seniors_count / $total) * 100)) : 0;

/* =========================
   STATUS COUNTS
========================= */

$active_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE record_status = 'active'
"))["cnt"] ?? 0;

$under_review_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE application_status = 'under review'
"))["cnt"] ?? 0;

$needs_correction_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE application_status = 'needs correction'
"))["cnt"] ?? 0;

$expired_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS cnt
    FROM residents
    WHERE record_status = 'expired'
"))["cnt"] ?? 0;

$status_total =
    $active_count +
    $under_review_count +
    $needs_correction_count +
    $expired_count;

/* =========================
   PIE CHART
========================= */

if (!function_exists('pieSlice')) {
function pieSlice($cx, $cy, $r, $startDeg, $endDeg) {

    $start = deg2rad($startDeg - 90);
    $end   = deg2rad($endDeg - 90);

    $large = ($endDeg - $startDeg) > 180 ? 1 : 0;

    $x1 = round($cx + $r * cos($start), 2);
    $y1 = round($cy + $r * sin($start), 2);

    $x2 = round($cx + $r * cos($end), 2);
    $y2 = round($cy + $r * sin($end), 2);

    return "M{$cx},{$cy}
            L{$x1},{$y1}
            A{$r},{$r}
            0 {$large},1 {$x2},{$y2}
            Z";
}
}

$active_deg =
    $status_total > 0
    ? ($active_count / $status_total) * 360
    : 0;

$under_review_deg =
    $status_total > 0
    ? ($under_review_count / $status_total) * 360
    : 0;

$needs_correction_deg =
    $status_total > 0
    ? ($needs_correction_count / $status_total) * 360
    : 0;

$expired_deg =
    $status_total > 0
    ? ($expired_count / $status_total) * 360
    : 0;

$active_path =
    $status_total > 0
    ? pieSlice(90,90,80,0,$active_deg)
    : "";

$under_review_path =
    $status_total > 0
    ? pieSlice(
        90,
        90,
        80,
        $active_deg,
        $active_deg + $under_review_deg
    )
    : "";

$needs_correction_path =
    $status_total > 0
    ? pieSlice(
        90,
        90,
        80,
        $active_deg + $under_review_deg,
        $active_deg + $under_review_deg + $needs_correction_deg
    )
    : "";

$expired_path =
    $status_total > 0
    ? pieSlice(
        90,
        90,
        80,
        $active_deg +
        $under_review_deg +
        $needs_correction_deg,
        360
    )
    : "";

/* =========================
   DIRECTORY
========================= */

$search = trim($_GET["search"] ?? "");

$query = "
SELECT
    residents.*,
    GROUP_CONCAT(
        resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type

FROM residents

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id
";

$where = [];

if (!empty($search)) {

    $safe = mysqli_real_escape_string($conn, $search);

    $where[] = "
    (
        CONCAT(
            first_name,
            ' ',
            middle_name,
            ' ',
            last_name
        ) LIKE '%$safe%'

        OR

        CONCAT(
            last_name,
            ', ',
            first_name
        ) LIKE '%$safe%'
    )";
}

if (!empty($_GET["category"])) {

    $cat = mysqli_real_escape_string(
        $conn,
        $_GET["category"]
    );

    $where[] = "
    resident_type = '$cat'
    ";
}

if (!empty($_GET["sex"])) {

    $sex = mysqli_real_escape_string(
        $conn,
        $_GET["sex"]
    );

    $where[] = "
    sex = '$sex'
    ";
}

if (!empty($_GET["status"])) {

    $status = mysqli_real_escape_string(
        $conn,
        $_GET["status"]
    );

    if ($status === "Active") {

        $where[] = "
        record_status = 'active'
        ";

    } elseif ($status === "Expired") {

        $where[] = "
        record_status = 'expired'
        ";

    } elseif ($status === "Under Review") {

        $where[] = "
        application_status = 'under review'
        ";

    } elseif ($status === "Needs Correction") {

        $where[] = "
        application_status = 'needs correction'
        ";
    }
}

if (!empty($_GET["disability"])) {

    $disability = mysqli_real_escape_string(
        $conn,
        $_GET["disability"]
    );

    $where[] = "
    resident_disabilities.disability_type
    = '$disability'
    ";
}

if (!empty($where)) {

    $query .= "
    WHERE " . implode(" AND ", $where);
}

$query .= "
GROUP BY residents.ID
ORDER BY residents.ID DESC
";

$residents_result = mysqli_query(
    $conn,
    $query
);

function badgeClass($type) {

    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"     => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
        "others"       => "badge-others",
    ];

    $key = strtolower(trim(explode(",", $type)[0]));

    return $map[$key]
        ?? "badge-physical";
}
?>