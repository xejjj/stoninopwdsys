<?php
require_once("db.php");
require_once("../fpdf/fpdf.php");

function getAge($birthdate) {
    if (empty($birthdate)) return "N/A";

    try {
        return date_diff(date_create($birthdate), date_create("today"))->y;
    } catch (Exception $e) {
        return "N/A";
    }
}

$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->Image('../assets/barangay-logo.png', 67, 10, 18);

$pdf->SetFont('Times', 'B', 10);
$pdf->SetTextColor(220, 0, 0);

$pdf->Cell(0, 6, 'SAMAHAN NG MGA PERSONS WITH DISABILITY NG BRGY. STO. NIÑO QC', 0, 1, 'C');
$pdf->Cell(0, 6, 'AREA XXII, DISTRICT IV', 0, 1, 'C');
$pdf->Cell(0, 6, '50 San Isidro St., Quezon City', 0, 1, 'C');

$pdf->Ln(8);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Times', 'B', 7);

$pdf->Cell(8, 8, 'NO.', 1, 0, 'C');
$pdf->Cell(62, 8, 'P.W.D. NAME', 1, 0, 'C');
$pdf->Cell(38, 8, 'GUARDIAN', 1, 0, 'C');
$pdf->Cell(38, 8, 'ADDRESS', 1, 0, 'C');
$pdf->Cell(22, 8, 'P.W.D ID NO.', 1, 0, 'C');
$pdf->Cell(18, 8, 'BIRTHDAY', 1, 0, 'C');
$pdf->Cell(10, 8, 'AGE', 1, 0, 'C');
$pdf->Cell(10, 8, 'SEX', 1, 0, 'C');
$pdf->Cell(25, 8, 'CONTACT NO.', 1, 0, 'C');
$pdf->Cell(32, 8, 'DISABILITY', 1, 0, 'C');
$pdf->Cell(23, 8, 'REMARKS', 1, 1, 'C');

$pdf->SetFont('Times', '', 6);

$sql = "
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

GROUP BY residents.ID

ORDER BY residents.last_name ASC
";

$result = mysqli_query($conn, $sql);

$counter = 1;

while ($row = mysqli_fetch_assoc($result)) {

    $fullName = strtoupper(
        ($row['last_name'] ?? '') . ", " .
        ($row['first_name'] ?? '') . " " .
        ($row['middle_name'] ?? '')
    );

    $guardian = strtoupper(substr($row['guardian_name'] ?? '', 0, 22));
    $address = strtoupper(substr($row['address'] ?? '', 0, 24));
    $remarks = strtoupper(substr($row['disability_remarks'] ?? '', 0, 16));
    $disability = strtoupper($row['disability_type'] ?? '');

    $pdf->Cell(8, 6, $counter, 1, 0, 'C');
    $pdf->Cell(62, 6, $fullName, 1);
    $pdf->Cell(38, 6, $guardian, 1);
    $pdf->Cell(38, 6, $address, 1);
    $pdf->Cell(22, 6, $row['pwdid_num'] ?? '', 1, 0, 'C');
    $pdf->Cell(18, 6, $row['birthdate'] ?? '', 1, 0, 'C');
    $pdf->Cell(10, 6, getAge($row['birthdate'] ?? null), 1, 0, 'C');
    $pdf->Cell(10, 6, strtoupper($row['sex'] ?? ''), 1, 0, 'C');
    $pdf->Cell(25, 6, $row['contact_num'] ?? '', 1, 0, 'C');
    $pdf->Cell(32, 6, $disability, 1, 0, 'C');
    $pdf->Cell(23, 6, $remarks, 1, 1, 'C');

    $counter++;
}

$pdf->Output('D', 'PWD_CWD_Residents_Report.pdf');
exit;
?>