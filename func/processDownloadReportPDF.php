<?php
date_default_timezone_set('Asia/Manila');
require_once("db.php");
require_once("../fpdf/fpdf.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? 'ALL';

// Multi-filter variables
$resType = $_GET['res_type'] ?? 'All';
$disType = $_GET['dis_type'] ?? 'All';
$sex = $_GET['sex'] ?? 'All';

// Variables from resident.php live filters
$search = trim($_GET["search"] ?? "");
$filter_cat = trim($_GET["category"] ?? "");
$filter_sex = trim($_GET["sex"] ?? "");
$filter_status = trim($_GET["status"] ?? "");
$filter_disab = trim($_GET["disability"] ?? "");

$whereCategory = "";
$disabilityFilter = "";

$allowedDisabilities = [
    "Cognitive", "Visual", "Physical", "Auditory", "Speech", "Psychosocial", "Others"
];

// Single Filter Logic
if (($type === "pwdcwd" || $type === "master") && ($category === "PWD" || $category === "CWD")) {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $whereCategory = "AND residents.resident_type = '$safeCategory'";
}

if ($type === "disability" && in_array($category, $allowedDisabilities)) {
    $safeDisability = mysqli_real_escape_string($conn, $category);
    $disabilityFilter = "AND EXISTS (
        SELECT 1 FROM resident_disabilities rd_filter
        WHERE rd_filter.resident_id = residents.ID
        AND rd_filter.disability_type = '$safeDisability'
    )";
}

// Multi-filter Logic
if ($type === "custom") {
    $conditions = [];
    if ($resType !== "All") {
        $safeRes = mysqli_real_escape_string($conn, $resType);
        $conditions[] = "residents.resident_type = '$safeRes'";
    }
    if ($sex !== "All") {
        $safeSex = mysqli_real_escape_string($conn, $sex);
        $conditions[] = "residents.sex = '$safeSex'";
    }
    if ($disType !== "All") {
        $safeDis = mysqli_real_escape_string($conn, $disType);
        $conditions[] = "EXISTS (SELECT 1 FROM resident_disabilities rd WHERE rd.resident_id = residents.ID AND rd.disability_type = '$safeDis')";
    }
    if (!empty($conditions)) {
        $whereCategory = "AND " . implode(" AND ", $conditions);
    }
}

// Resident.php dynamic filters logic
if ($type === "resident_list") {
    $conditions = [];
    if (!empty($search)) {
        $safe = mysqli_real_escape_string($conn, $search);
        $conditions[] = "(residents.first_name LIKE '%$safe%' OR residents.middle_name LIKE '%$safe%' OR residents.last_name LIKE '%$safe%')";
    }
    if (!empty($filter_cat)) {
        $safe = mysqli_real_escape_string($conn, $filter_cat);
        $conditions[] = "residents.resident_type = '$safe'";
    }
    if (!empty($filter_sex)) {
        $safe = mysqli_real_escape_string($conn, strtolower($filter_sex));
        $conditions[] = "residents.sex = '$safe'";
    }
    if (!empty($filter_disab)) {
        $safe = mysqli_real_escape_string($conn, $filter_disab);
        $conditions[] = "EXISTS (SELECT 1 FROM resident_disabilities rd WHERE rd.resident_id = residents.ID AND rd.disability_type = '$safe')";
    }
    if (!empty($filter_status)) {
        if ($filter_status === "Active") $conditions[] = "residents.record_status = 'active'";
        elseif ($filter_status === "Expired") $conditions[] = "residents.record_status = 'expired'";
        elseif ($filter_status === "Rejected") $conditions[] = "residents.application_status = 'rejected'";
        elseif ($filter_status === "Under Review") $conditions[] = "residents.application_status = 'under review'";
        elseif ($filter_status === "Needs Correction") $conditions[] = "residents.application_status = 'needs correction'";
    }
    if (!empty($conditions)) {
        $whereCategory = "AND " . implode(" AND ", $conditions);
    }
}

function getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab) {
    if ($type === "resident_list") {
        $parts = [];
        if (!empty($filter_cat)) $parts[] = $filter_cat;
        if (!empty($filter_sex)) $parts[] = ucfirst($filter_sex);
        if (!empty($filter_status)) $parts[] = $filter_status;
        if (!empty($filter_disab)) $parts[] = $filter_disab;
        if (!empty($search)) $parts[] = "Search: '" . $search . "'";
        if (empty($parts)) return "Complete Residents List";
        return "Filtered Residents List: " . implode(" | ", $parts);
    }
    if ($type === "custom") {
        $parts = [];
        if ($resType !== "All") $parts[] = $resType;
        if ($sex !== "All") $parts[] = $sex;
        if ($disType !== "All") $parts[] = $disType;
        if (empty($parts)) return "Complete Master List";
        return "Filtered List: " . implode(" | ", $parts);
    }
    if ($type === "master") return "Master List of Registered PWDs and CWDs";
    if ($type === "pwdcwd") return $category === "ALL" ? "PWD/CWD Summary Report" : "$category Summary Report";
    if ($type === "disability") return $category === "ALL" ? "PWD/CWD Disability Classification Summary" : "$category Disability Classification Details";
    return "Report";
}

function getAge($birthdate) {
    if (empty($birthdate) || $birthdate === '0000-00-00') return "--";
    try {
        return date_diff(date_create($birthdate), date_create("today"))->y;
    } catch (Exception $e) {
        return "--";
    }
}

function fmt($val) {
    $trimVal = trim($val ?? '');
    if ($trimVal === '' || $trimVal === 'N/A' || $trimVal === '0000-00-00') return "--";
    return htmlspecialchars($trimVal);
}

function fmtDate($val) {
    $trimVal = trim($val ?? '');
    if ($trimVal === '' || $trimVal === 'N/A' || $trimVal === '0000-00-00') return "--";
    // Changed to Y-m-d to match the column header formatting
    return date('Y-m-d', strtotime($trimVal));
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

// Extension of FPDF to handle repeating headers
class ReportPDF extends FPDF {
    public $reportTitle = '';
    public $tableCols = [];

    function Header() {
        $this->Image('../assets/barangay-logo.png', 92, 10, 25);
        $this->Ln(30);
        $this->SetFont('Times', 'B', 12);
        $this->SetTextColor(180, 0, 0);
        $this->Cell(0, 6, utf8_decode('SAMAHAN NG MGA PERSONS WITH DISABILITY NG BRGY. STO. NIÑO QC'), 0, 1, 'C');
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 5, 'Area XXII, District IV', 0, 1, 'C');
        $this->Cell(0, 5, '50 San Isidro St., Quezon City', 0, 1, 'C');
        $this->Cell(0, 5, 'Report Generated: ' . date('Y-m-d h:i A'), 0, 1, 'C');
        $this->Ln(4);
        
        $this->SetFont('Times', 'B', 12); 
        $this->Cell(0, 8, utf8_decode(strtoupper($this->reportTitle)), 0, 1, 'C');
        $this->Ln(4);

        if (!empty($this->tableCols)) {
            $this->SetFont('Times', 'B', 6);
            $this->SetFillColor(240, 240, 240);
            
            $startY = $this->GetY();
            foreach ($this->tableCols as $col) {
                $startX = $this->GetX();
                
                // Draw standard cell background/border manually
                $this->Rect($startX, $startY, $col['w'], 8, 'DF');
                
                // Detect linebreaks and split text
                if (strpos($col['label'], "\n") !== false) {
                    $lines = explode("\n", $col['label']);
                    $this->SetXY($startX, $startY + 1.5);
                    $this->Cell($col['w'], 2.5, $lines[0], 0, 2, 'C');
                    $this->Cell($col['w'], 2.5, $lines[1], 0, 0, 'C');
                } else {
                    $this->SetXY($startX, $startY);
                    $this->Cell($col['w'], 8, $col['label'], 0, 0, 'C');
                }
                
                // Advance X to the next column
                $this->SetXY($startX + $col['w'], $startY);
            }
            $this->Ln(8);
        }
    }
}

$pdfTitle = getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab);

$pdf = new ReportPDF('P', 'mm', 'A4');
$pdf->reportTitle = $pdfTitle;
$pdf->SetMargins(10, 10, 10); 

if ($type === "master" || $type === "custom" || $type === "resident_list") {
    $pdf->tableCols = [
        ['w' => 8,  'label' => 'No.'],
        ['w' => 32, 'label' => 'Full Name'],
        ['w' => 12, 'label' => 'Type'],
        ['w' => 8,  'label' => 'Sex'],
        ['w' => 7,  'label' => 'Age'],
        ['w' => 16, 'label' => "Birthdate\n(yyyy-mm-dd)"],
        ['w' => 30, 'label' => 'Address'],
        ['w' => 18, 'label' => 'Contact No.'],
        ['w' => 20, 'label' => 'Guardian'],
        ['w' => 17, 'label' => 'PWD ID'],
        ['w' => 22, 'label' => 'Disability']
    ];
    $sql = getResidentReportSql($whereCategory);

} elseif ($type === "pwdcwd") {
    $pdf->tableCols = [
        ['w' => 8,  'label' => 'No.'],
        ['w' => 32, 'label' => 'Full Name'],
        ['w' => 8,  'label' => 'Sex'],
        ['w' => 7,  'label' => 'Age'],
        ['w' => 16, 'label' => "Birthdate\n(yyyy-mm-dd)"],
        ['w' => 30, 'label' => 'Address'],
        ['w' => 18, 'label' => 'Contact No.'],
        ['w' => 20, 'label' => 'Guardian'],
        ['w' => 17, 'label' => 'PWD ID'],
        ['w' => 18, 'label' => 'Disability'],
        ['w' => 16, 'label' => 'Remarks']
    ];
    $sql = getResidentReportSql($whereCategory);

} elseif ($type === "disability") {
    $pdf->tableCols = [
        ['w' => 7,  'label' => 'No.'],
        ['w' => 28, 'label' => 'Full Name'],
        ['w' => 10, 'label' => 'Type'],
        ['w' => 7,  'label' => 'Sex'],
        ['w' => 6,  'label' => 'Age'],
        ['w' => 15, 'label' => "Birthdate\n(yyyy-mm-dd)"],
        ['w' => 28, 'label' => 'Address'],
        ['w' => 16, 'label' => 'Contact No.'],
        ['w' => 18, 'label' => 'Guardian'],
        ['w' => 15, 'label' => 'PWD ID'],
        ['w' => 20, 'label' => 'Disability'],
        ['w' => 20, 'label' => 'Remarks']
    ];
    $sql = getResidentReportSql($disabilityFilter);
} else {
    $pdf->AddPage();
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, 10, 'Invalid report type.', 0, 1, 'C');
    $pdf->Output('D', 'Invalid_Report.pdf');
    exit;
}

$pdf->AddPage();
$pdf->SetFont('Times', '', 6);
$result = mysqli_query($conn, $sql);
$no = 1;

$w = array_column($pdf->tableCols, 'w');

while ($row = mysqli_fetch_assoc($result)) {
    $fullName = utf8_decode(strtoupper(
        ($row['last_name'] ?? '') . ', ' .
        ($row['first_name'] ?? '') . ' ' .
        ($row['middle_name'] ?? '')
    ));

    $resType   = utf8_decode(strtoupper(fmt($row['resident_type'])));
    
    $s = trim($row['sex'] ?? '');
    $genderChar = ($s === '' || strtolower($s) === 'n/a') ? '--' : strtoupper(substr($s, 0, 1));
    
    $age       = getAge($row['birthdate']);
    $bday      = fmtDate($row['birthdate']);
    $address   = utf8_decode(strtoupper(fmt($row['address'])));
    $contact   = utf8_decode(strtoupper(fmt($row['contact_num'])));
    $guardian  = utf8_decode(strtoupper(fmt($row['guardian_name'])));
    $pwdId     = utf8_decode(strtoupper(fmt($row['pwdid_num'])));
    $disab     = utf8_decode(strtoupper(fmt($row['disability_type'])));
    $remarks   = utf8_decode(strtoupper(fmt($row['disability_remarks'])));

    if ($type === "master" || $type === "custom" || $type === "resident_list") {
        $pdf->Cell($w[0],  6, $no++, 1, 0, 'C');
        $pdf->Cell($w[1],  6, substr($fullName, 0, 22), 1);
        $pdf->Cell($w[2],  6, substr($resType, 0, 8), 1, 0, 'C');
        $pdf->Cell($w[3],  6, $genderChar, 1, 0, 'C');
        $pdf->Cell($w[4],  6, $age, 1, 0, 'C');
        $pdf->Cell($w[5],  6, $bday, 1, 0, 'C');
        $pdf->Cell($w[6],  6, substr($address, 0, 20), 1);
        $pdf->Cell($w[7],  6, substr($contact, 0, 11), 1, 0, 'C');
        $pdf->Cell($w[8],  6, substr($guardian, 0, 14), 1);
        $pdf->Cell($w[9],  6, substr($pwdId, 0, 11), 1, 0, 'C');
        $pdf->Cell($w[10], 6, substr($disab, 0, 15), 1, 1, 'C');

    } elseif ($type === "pwdcwd") {
        $pdf->Cell($w[0],  6, $no++, 1, 0, 'C');
        $pdf->Cell($w[1],  6, substr($fullName, 0, 22), 1);
        $pdf->Cell($w[2],  6, $genderChar, 1, 0, 'C');
        $pdf->Cell($w[3],  6, $age, 1, 0, 'C');
        $pdf->Cell($w[4],  6, $bday, 1, 0, 'C');
        $pdf->Cell($w[5],  6, substr($address, 0, 20), 1);
        $pdf->Cell($w[6],  6, substr($contact, 0, 11), 1, 0, 'C');
        $pdf->Cell($w[7],  6, substr($guardian, 0, 14), 1);
        $pdf->Cell($w[8],  6, substr($pwdId, 0, 11), 1, 0, 'C');
        $pdf->Cell($w[9],  6, substr($disab, 0, 12), 1, 0, 'C');
        $pdf->Cell($w[10], 6, substr($remarks, 0, 10), 1, 1, 'C');

    } elseif ($type === "disability") {
        $pdf->Cell($w[0],  6, $no++, 1, 0, 'C');
        $pdf->Cell($w[1],  6, substr($fullName, 0, 19), 1);
        $pdf->Cell($w[2],  6, substr($resType, 0, 6), 1, 0, 'C');
        $pdf->Cell($w[3],  6, $genderChar, 1, 0, 'C');
        $pdf->Cell($w[4],  6, $age, 1, 0, 'C');
        $pdf->Cell($w[5],  6, substr($bday, 2), 1, 0, 'C'); 
        $pdf->Cell($w[6],  6, substr($address, 0, 18), 1);
        $pdf->Cell($w[7],  6, substr($contact, 0, 11), 1, 0, 'C');
        $pdf->Cell($w[8],  6, substr($guardian, 0, 12), 1);
        $pdf->Cell($w[9],  6, substr($pwdId, 0, 10), 1, 0, 'C');
        $pdf->Cell($w[10], 6, substr($disab, 0, 14), 1, 0, 'C');
        $pdf->Cell($w[11], 6, substr($remarks, 0, 14), 1, 1, 'C');
    }
}

$pdf->Ln(20);

if ($pdf->GetY() > 240) {
    $pdf->AddPage();
    $pdf->Ln(20);
}

$pdf->SetFont('Times', '', 11);
$pdf->Cell(95, 6, 'Prepared by:', 0, 0, 'C');
$pdf->Cell(95, 6, 'Noted by:', 0, 1, 'C');

$pdf->Ln(15);

$pdf->Cell(95, 6, '________________________________', 0, 0, 'C');
$pdf->Cell(95, 6, '________________________________', 0, 1, 'C');

$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(95, 5, 'Name', 0, 0, 'C');
$pdf->Cell(95, 5, 'Name', 0, 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(95, 5, 'Title / Designation', 0, 0, 'C');
$pdf->Cell(95, 5, 'Title / Designation', 0, 1, 'C');

$safeFilename = str_replace([' ', '/', '|', ':'], '_', $pdfTitle);
$pdf->Output('D', $safeFilename . '.pdf');
exit;
?>