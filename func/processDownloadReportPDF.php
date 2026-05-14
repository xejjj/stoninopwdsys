<?php
require_once("db.php");
require_once("../fpdf/fpdf.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? 'ALL';

$whereCategory = "";

if ($category === "PWD" || $category === "CWD") {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $whereCategory = "AND residents.resident_type = '$safeCategory'";
}

function getTitle($type, $category) {
    if ($type === "master") return "Master List of Registered PWDs and CWDs";
    if ($type === "pwdcwd") return $category === "ALL" ? "PWD/CWD Summary Report" : "$category Summary Report";
    if ($type === "disability") return $category === "ALL" ? "PWD/CWD Disability Classification Summary" : "$category Disability Classification Summary";
    return "Report";
}

function getAge($birthdate) {
    if (empty($birthdate)) return "N/A";

    try {
        return date_diff(
            date_create($birthdate),
            date_create("today")
        )->y;
    } catch (Exception $e) {
        return "N/A";
    }
}

function getResidentReportSql($whereCategory = "") {
    return "
    SELECT
        residents.*,

        resident_contacts.contact_num,

        GROUP_CONCAT(
            DISTINCT resident_disabilities.disability_type
            SEPARATOR ', '
        ) AS disability_type,

        MAX(
            CASE
                WHEN resident_family_members.relationship
                NOT IN ('Father','Mother','Spouse')
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
    $whereCategory

    GROUP BY residents.ID

    ORDER BY residents.resident_type, residents.last_name, residents.first_name
    ";
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
    $pdf->Cell(30, 8, 'Disability', 1, 1, 'C');

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
        $pdf->Cell(30, 7, substr($row['disability_type'] ?? 'N/A', 0, 22), 1, 1, 'C');
    }

} elseif ($type === "disability") {

    $sql = "
    SELECT
        resident_disabilities.disability_type,
        residents.resident_type,
        COUNT(DISTINCT residents.ID) AS total

    FROM resident_disabilities

    LEFT JOIN residents
    ON resident_disabilities.resident_id = residents.ID

    WHERE residents.record_status != 'archived'
    $whereCategory

    GROUP BY
        resident_disabilities.disability_type,
        residents.resident_type

    ORDER BY
        resident_disabilities.disability_type,
        residents.resident_type
    ";

    $result = mysqli_query($conn, $sql);

    $pdf->SetFont('Times', 'B', 9);
    $pdf->Cell(90, 9, 'Disability Type', 1, 0, 'C');
    $pdf->Cell(70, 9, 'Resident Type', 1, 0, 'C');
    $pdf->Cell(40, 9, 'Total', 1, 1, 'C');

    $pdf->SetFont('Times', '', 9);

    while ($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(90, 8, $row['disability_type'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(70, 8, $row['resident_type'] ?? 'N/A', 1, 0, 'C');
        $pdf->Cell(40, 8, $row['total'] ?? 0, 1, 1, 'C');
    }

} else {
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, 10, 'Invalid report type.', 0, 1, 'C');
}

$pdf->Output('D', str_replace(' ', '_', getTitle($type, $category)) . '.pdf');
exit;
?>