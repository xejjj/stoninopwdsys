<?php
require_once("db.php");

require('../fpdf/fpdf.php');

$pdf = new FPDF('L', 'mm', 'A4');

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(
    277,
    10,
    'PWD/CWD Residents Report',
    0,
    1,
    'C'
);

$pdf->Ln(5);

/* TABLE HEADERS */

$pdf->SetFont('Arial', 'B', 8);

$pdf->Cell(45,10,'Full Name',1);
$pdf->Cell(35,10,'Guardian',1);
$pdf->Cell(50,10,'Address',1);
$pdf->Cell(28,10,'PWD ID No.',1);
$pdf->Cell(25,10,'Birthday',1);
$pdf->Cell(12,10,'Age',1);
$pdf->Cell(15,10,'Sex',1);
$pdf->Cell(30,10,'Contact No.',1);
$pdf->Cell(28,10,'Disability',1);
$pdf->Cell(35,10,'Remarks',1);

$pdf->Ln();

/* TABLE CONTENT */

$pdf->SetFont('Arial', '', 7);

$result =
    mysqli_query($conn,
    "SELECT * FROM residents");

while ($row = mysqli_fetch_assoc($result)) {

    $fullName =
        $row['last_name'] . ", " .
        $row['first_name'] . " " .
        $row['middle_name'];

    $pdf->Cell(45,10,$fullName,1);

    $pdf->Cell(
        35,
        10,
        $row['guardian_name'],
        1
    );

    $pdf->Cell(
        50,
        10,
        substr($row['address'],0,30),
        1
    );

    $pdf->Cell(
        28,
        10,
        $row['pwdid_num'],
        1
    );

    $pdf->Cell(
        25,
        10,
        $row['birthdate'],
        1
    );

    $pdf->Cell(
        12,
        10,
        $row['age'],
        1
    );

    $pdf->Cell(
        15,
        10,
        ucfirst($row['sex']),
        1
    );

    $pdf->Cell(
        30,
        10,
        $row['contact_num'],
        1
    );

    $pdf->Cell(
        28,
        10,
        $row['disablity_type'],
        1
    );

    $pdf->Cell(
        35,
        10,
        substr($row['disability_remarks'],0,20),
        1
    );

    $pdf->Ln();
}

$pdf->Output(
    'D',
    'PWD_CWD_Residents_Report.pdf'
);
?>