<?php
require_once("db.php");
require_once("../fpdf/fpdf.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? 'ALL';

$whereCategory = "";
$disabilityFilter = "";

$allowedDisabilities = [
    "Cognitive",
    "Visual",
    "Physical",
    "Auditory",
    "Speech",
    "Psychosocial",
    "Others"
];

if (($type === "pwdcwd" || $type === "master") && ($category === "PWD" || $category === "CWD")) {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $whereCategory = "AND residents.resident_type = '$safeCategory'";
}

if ($type === "disability" && in_array($category, $allowedDisabilities)) {
    $safeDisability = mysqli_real_escape_string($conn, $category);
    $disabilityFilter = "AND EXISTS (
        SELECT 1
        FROM resident_disabilities rd_filter
        WHERE rd_filter.resident_id = residents.ID
        AND rd_filter.disability_type = '$safeDisability'
    )";
}

function getTitle($type, $category) {
    if ($type === "master") return "Master List of Registered PWDs and CWDs";
    if ($type === "pwdcwd") return $category === "ALL" ? "PWD/CWD Summary Report" : "$category Summary Report";
    if ($type === "disability") return $category === "ALL" ? "PWD/CWD Disability Classification Summary" : "$category Disability Classification Details";
    return "Report";
}

function getAge($birthdate) {
    if (empty($birthdate)) return "N/A";

    try {
        return date_diff(date_create($birthdate), date_create("today"))->y;
    } catch (Exception $e) {
        return "N/A";
    }
}

function getResidentReportSql($extraWhere = "") {
    return "
    SELECT
        residents.*,
        resident_contacts.contact_num,

        GROUP_CONCAT(
            DISTINCT resident_disabilities.disability_type
            SEPARATOR ', '
        ) AS disability_type,

        MAX(resident_disabilities.notes) AS disability_remarks,

        MAX(
            CASE
                WHEN resident_family_members.relationship NOT IN ('Father','Mother','Spouse')
                THEN resident_family_members.name
            END
        ) AS guardian_name

    FROM residents

    LEFT JOIN resident_contacts
    ON residents.ID = resident_contacts.resident_id

    LEFT JOIN resident_disabilities
    ON residents.ID = resident_disabilities.resident_id

    LEFT JOIN resident_family_members
    ON residents.ID = resident_family_members.resident_id

    WHERE residents.record_status != 'archived'
    $extraWhere

    GROUP BY residents.ID

    ORDER BY residents.resident_type, residents.last_name, residents.first_name
    ";
}

function printResidentTable($pdf, $result, $showDisability = true) {
    $pdf->SetFont('Times', 'B', 7);

    $pdf->Cell(8, 8, 'No.', 1, 0, 'C');
    $pdf->Cell(45, 8, 'Full Name', 1, 0, 'C');
    $pdf->Cell(18, 8, 'Type', 1, 0, 'C');
    $pdf->Cell(12, 8, 'Sex', 1, 0, 'C');
    $pdf->Cell(10, 8, 'Age', 1, 0, 'C');
    $pdf->Cell(22, 8, 'Birthdate', 1, 0, 'C');
    $pdf->Cell(48, 8, 'Address', 1, 0, 'C');
    $pdf->Cell(28, 8, 'Contact No.', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Guardian', 1, 0, 'C');
    $pdf->Cell(28, 8, 'PWD ID No.', 1, 0, 'C');
    if ($showDisability) {
    $pdf->Cell(30, 8, 'Disability', 1, 1, 'C');
} else {
    $pdf->Cell(0, 8, '', 0, 1);
}

    $pdf->SetFont('Times', '', 6);
    $no = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $fullName = strtoupper(
            ($row['last_name'] ?? '') . ', ' .
            ($row['first_name'] ?? '') . ' ' .
            ($row['middle_name'] ?? '')
        );

        $pdf->Cell(8, 7, $no++, 1, 0, 'C');
        $pdf->Cell(45, 7, substr($fullName, 0, 32), 1);
        $pdf->Cell(18, 7, $row['resident_type'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(12, 7, strtoupper($row['sex'] ?? 'N/A'), 1, 0, 'C');
        $pdf->Cell(10, 7, getAge($row['birthdate'] ?? null), 1, 0, 'C');
        $pdf->Cell(22, 7, $row['birthdate'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(48, 7, substr($row['address'] ?? 'N/A', 0, 35), 1);
        $pdf->Cell(28, 7, $row['contact_num'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(35, 7, substr($row['guardian_name'] ?? 'N/A', 0, 24), 1);
        $pdf->Cell(28, 7, $row['pwdid_num'] ?? 'N/A', 1, 0, 'C');
        if ($showDisability) {
        $pdf->Cell(30, 7, substr($row['disability_type'] ?? 'N/A', 0, 22), 1, 1, 'C');
} else {
    $pdf->Ln();
}
    }
}

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->Image('../assets/barangay-logo.png', 132, 8, 25);

$pdf->SetFont('Times', 'B', 11);
$pdf->SetTextColor(180, 0, 0);
$pdf->Ln(28);
$pdf->Cell(0, 6, 'SAMAHAN NG MGA PERSONS WITH DISABILITY NG BRGY. STO. NIÑO QC', 0, 1, 'C');
$pdf->Cell(0, 6, 'AREA XXII, DISTRICT IV', 0, 1, 'C');
$pdf->Cell(0, 6, '50 San Isidro St., Quezon City', 0, 1, 'C');

$pdf->Ln(6);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 8, strtoupper(getTitle($type, $category)), 0, 1, 'C');
$pdf->Ln(3);

if ($type === "master" || $type === "pwdcwd") {
    $sql = getResidentReportSql($whereCategory);
    $result = mysqli_query($conn, $sql);
    printResidentTable($pdf, $result);

}  elseif ($type === "disability") {
    $sql = getResidentReportSql($disabilityFilter);
    $result = mysqli_query($conn, $sql);
    printResidentTable($pdf, $result, false);

} else {
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, 10, 'Invalid report type.', 0, 1, 'C');
}

$pdf->Output('D', str_replace(' ', '_', getTitle($type, $category)) . '.pdf');
exit;
?>