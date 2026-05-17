<?php
date_default_timezone_set('Asia/Manila');
require_once("db.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? '';

// Multi-filter variables (from reports.php custom form)
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
    "Cognitive",
    "Visual",
    "Physical",
    "Auditory",
    "Speech",
    "Psychosocial",
    "Others"
];

if ($type === "pwdcwd") {
    if ($category === "PWD" || $category === "CWD") {
        $safeCategory = mysqli_real_escape_string($conn, $category);
        $whereCategory = "AND residents.resident_type = '$safeCategory'";
    }
}

if ($type === "disability" && in_array($category, $allowedDisabilities)) {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $disabilityFilter = "AND resident_disabilities.disability_type = '$safeCategory'";
}

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

// Logic specifically for matching getResidents.php dynamic filters
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
        if ($filter_status === "Active") {
            $conditions[] = "residents.record_status = 'active'";
        } elseif ($filter_status === "Expired") {
            $conditions[] = "residents.record_status = 'expired'";
        } elseif ($filter_status === "Rejected") {
            $conditions[] = "residents.application_status = 'rejected'";
        } elseif ($filter_status === "Under Review") {
            $conditions[] = "residents.application_status = 'under review'";
        } elseif ($filter_status === "Needs Correction") {
            $conditions[] = "residents.application_status = 'needs correction'";
        }
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

    if ($type === "master") {
        if ($category === "All" || empty($category)) return "Master List of Registered PWDs and CWDs";
        if ($category === "PWD") return "Master List of Registered PWDs";
        if ($category === "CWD") return "Master List of Registered CWDs";
        return "Master List of Registered Residents - " . $category;
    }

    if ($type === "pwdcwd") {
        if ($category === "PWD") return "PWD Summary Report";
        if ($category === "CWD") return "CWD Summary Report";
        return "PWD/CWD Summary Report";
    }

    if ($type === "disability") {
        if (!empty($category)) {
            return $category . " Disability Classification Summary";
        }
        return "Disability Classification Summary";
    }

    return "Report";
}

function getAge($birthdate) {
    if (empty($birthdate) || $birthdate === '0000-00-00') {
        return "--";
    }
    try {
        return date_diff(
            date_create($birthdate),
            date_create("today")
        )->y;
    } catch (Exception $e) {
        return "--";
    }
}

function fmt($val) {
    $trimVal = trim($val ?? '');
    if ($trimVal === '' || $trimVal === 'N/A' || $trimVal === '0000-00-00') {
        return "--";
    }
    return htmlspecialchars($trimVal);
}

function fmtDate($val) {
    $trimVal = trim($val ?? '');
    if ($trimVal === '' || $trimVal === 'N/A' || $trimVal === '0000-00-00') {
        return "--";
    }
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
        MAX(resident_disabilities.notes)
        AS disability_remarks,
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
    $extraWhere 
    GROUP BY residents.ID
    ORDER BY residents.resident_type, residents.last_name, residents.first_name
    ";
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab); ?></title>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    color: #111;
    margin: 0;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th, .data-table td {
    border: 1px solid #333;
    padding: 7px;
    font-size: 12px;
    text-align: center;
}

.data-table th {
    background: #f1f1f1;
    font-weight: bold;
}

.col-name {
    text-align: left !important;
}

.report-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.print-btn {
    padding: 10px 18px;
    border: none;
    background: #A84040;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.print-header-block {
    text-align: center;
    padding-bottom: 15px;
    background: white;
}

.signatories {
    display: flex;
    justify-content: space-around;
    margin-top: 50px; 
    page-break-inside: avoid;
    break-inside: avoid; 
}

.sign-box {
    text-align: center;
    width: 250px;
}

.sign-box p {
    margin: 5px 0;
    font-size: 14px;
}

.signature-line {
    border-bottom: 1px solid #111;
    height: 40px;
    margin-bottom: 5px;
}

@page {
    margin: 15mm; 
}

@media print {
    .print-btn, .report-actions {
        display: none !important;
    }

    body {
        padding: 0;
        margin: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .data-table {
        page-break-inside: auto !important;
        width: 100%;
    }

    /* Force the header to repeat correctly on every page */
    thead {
        display: table-header-group !important;
    }

    tbody {
        display: table-row-group !important;
    }

    tr {
        page-break-inside: avoid !important;
        page-break-after: auto !important;
    }

    .data-table th, .data-table td {
        font-size: 10px;
        padding: 5px;
    }

    .signatories {
        margin-top: 30px; 
        page-break-before: auto; 
    }
}
</style>
</head>

<body>

<div class="report-actions">
    <button class="print-btn" onclick="window.print()">Print Report</button>
    <a class="print-btn" href="processDownloadReportPDF.php?<?php echo htmlspecialchars($_SERVER['QUERY_STRING']); ?>">
        Download PDF
    </a>
</div>

<?php if ($type === "custom" || $type === "master" || $type === "resident_list") : ?>

    <?php
    $sql = getResidentReportSql($whereCategory);
    $result = mysqli_query($conn, $sql);
    ?>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="12" style="border: none; background: white; padding: 0;">
                    <div class="print-header-block">
                        <img src="../assets/barangay-logo.png" style="width: 70px; margin-bottom: 5px;"><br>
                        <strong style="font-size: 18px; color: #b22222; text-transform: uppercase;">Samahan ng mga Persons with Disability ng Brgy. Sto. Niño QC</strong><br>
                        <span style="font-size: 13px; font-weight: normal;">Area XXII, District IV</span><br>
                        <span style="font-size: 13px; font-weight: normal;">50 San Isidro St., Quezon City</span><br>
                        <span style="font-size: 13px; font-weight: normal;">Report Generated: <?php echo date('Y-m-d h:i A'); ?></span><br><br>
                        <strong style="font-size: 18px; text-transform: uppercase;"><?php echo getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab); ?></strong>
                    </div>
                </th>
            </tr>
            <tr>
                <th>No.</th>
                <th class="col-name">Full Name</th>
                <th>Resident Type</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Birthdate<br><small>(yyyy-mm-dd)</small></th>
                <th>Address</th>
                <th>Contact No.</th>
                <th>Guardian</th>
                <th>PWD ID No.</th>
                <th>Disability Type</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <?php
                $fullName =
                    ($row['last_name'] ?? '') . ", " .
                    ($row['first_name'] ?? '') . " " .
                    ($row['middle_name'] ?? '');
                ?>

                <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="col-name"><?php echo fmt($fullName); ?></td>
                    <td><?php echo fmt($row['resident_type']); ?></td>
                    <td><?php echo ucfirst(fmt($row['sex'])); ?></td>
                    <td><?php echo getAge($row['birthdate']); ?></td>
                    <td><?php echo fmtDate($row['birthdate']); ?></td>
                    <td><?php echo fmt($row['address']); ?></td>
                    <td><?php echo fmt($row['contact_num']); ?></td>
                    <td><?php echo fmt($row['guardian_name']); ?></td>
                    <td><?php echo fmt($row['pwdid_num']); ?></td>
                    <td><?php echo fmt($row['disability_type']); ?></td>
                    <td><?php echo fmt($row['disability_remarks']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php elseif ($type === "pwdcwd") : ?>

    <?php
    $sql = getResidentReportSql($whereCategory);
    $result = mysqli_query($conn, $sql);
    ?>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="11" style="border: none; background: white; padding: 0;">
                    <div class="print-header-block">
                        <img src="../assets/barangay-logo.png" style="width: 70px; margin-bottom: 5px;"><br>
                        <strong style="font-size: 18px; color: #b22222; text-transform: uppercase;">Samahan ng mga Persons with Disability ng Brgy. Sto. Niño QC</strong><br>
                        <span style="font-size: 13px; font-weight: normal;">Area XXII, District IV</span><br>
                        <span style="font-size: 13px; font-weight: normal;">50 San Isidro St., Quezon City</span><br>
                        <span style="font-size: 13px; font-weight: normal;">Report Generated: <?php echo date('Y-m-d h:i A'); ?></span><br><br>
                        <strong style="font-size: 18px; text-transform: uppercase;"><?php echo getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab); ?></strong>
                    </div>
                </th>
            </tr>
            <tr>
                <th>No.</th>
                <th class="col-name">Full Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Birthdate<br><small>(yyyy-mm-dd)</small></th>
                <th>Address</th>
                <th>Contact No.</th>
                <th>Guardian</th>
                <th>PWD ID No.</th>
                <th>Disability Type</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <?php
                $fullName =
                    ($row['last_name'] ?? '') . ", " .
                    ($row['first_name'] ?? '') . " " .
                    ($row['middle_name'] ?? '');
                ?>

                <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="col-name"><?php echo fmt($fullName); ?></td>
                    <td><?php echo ucfirst(fmt($row['sex'])); ?></td>
                    <td><?php echo getAge($row['birthdate']); ?></td>
                    <td><?php echo fmtDate($row['birthdate']); ?></td>
                    <td><?php echo fmt($row['address']); ?></td>
                    <td><?php echo fmt($row['contact_num']); ?></td>
                    <td><?php echo fmt($row['guardian_name']); ?></td>
                    <td><?php echo fmt($row['pwdid_num']); ?></td>
                    <td><?php echo fmt($row['disability_type']); ?></td>
                    <td><?php echo fmt($row['disability_remarks']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php elseif ($type === "disability") : ?>

    <?php
    $sql = getResidentReportSql($disabilityFilter);
    $result = mysqli_query($conn, $sql);
    ?>

    <table class="data-table">
        <thead>
            <tr>
                <th colspan="12" style="border: none; background: white; padding: 0;">
                    <div class="print-header-block">
                        <img src="../assets/barangay-logo.png" style="width: 70px; margin-bottom: 5px;"><br>
                        <strong style="font-size: 18px; color: #b22222; text-transform: uppercase;">Samahan ng mga Persons with Disability ng Brgy. Sto. Niño QC</strong><br>
                        <span style="font-size: 13px; font-weight: normal;">Area XXII, District IV</span><br>
                        <span style="font-size: 13px; font-weight: normal;">50 San Isidro St., Quezon City</span><br>
                        <span style="font-size: 13px; font-weight: normal;">Report Generated: <?php echo date('Y-m-d h:i A'); ?></span><br><br>
                        <strong style="font-size: 18px; text-transform: uppercase;"><?php echo getTitle($type, $category, $resType, $disType, $sex, $search, $filter_cat, $filter_sex, $filter_status, $filter_disab); ?></strong>
                    </div>
                </th>
            </tr>
            <tr>
                <th>No.</th>
                <th class="col-name">Full Name</th>
                <th>Resident Type</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Birthdate<br><small>(yyyy-mm-dd)</small></th>
                <th>Address</th>
                <th>Contact No.</th>
                <th>Guardian</th>
                <th>PWD ID No.</th>
                <th>Disability Type</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <?php
                $fullName =
                    ($row['last_name'] ?? '') . ", " .
                    ($row['first_name'] ?? '') . " " .
                    ($row['middle_name'] ?? '');
                ?>

                <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="col-name"><?php echo fmt($fullName); ?></td>
                    <td><?php echo fmt($row['resident_type']); ?></td>
                    <td><?php echo ucfirst(fmt($row['sex'])); ?></td>
                    <td><?php echo getAge($row['birthdate']); ?></td>
                    <td><?php echo fmtDate($row['birthdate']); ?></td>
                    <td><?php echo fmt($row['address']); ?></td>
                    <td><?php echo fmt($row['contact_num']); ?></td>
                    <td><?php echo fmt($row['guardian_name']); ?></td>
                    <td><?php echo fmt($row['pwdid_num']); ?></td>
                    <td><?php echo fmt($row['disability_type']); ?></td>
                    <td><?php echo fmt($row['disability_remarks']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php else : ?>

    <p style="text-align:center;">Invalid report type.</p>

<?php endif; ?>

<?php if ($type === "custom" || $type === "master" || $type === "pwdcwd" || $type === "disability" || $type === "resident_list") : ?>
<div class="signatories">
    <div class="sign-box">
        <p>Prepared by:</p>
        <div class="signature-line"></div>
        <p>Name</p>
        <p>Title / Designation</p>
    </div>
    <div class="sign-box">
        <p>Noted by:</p>
        <div class="signature-line"></div>
        <p>Name</p>
        <p>Title / Designation</p>
    </div>
</div>
<?php endif; ?>

</body>
</html>