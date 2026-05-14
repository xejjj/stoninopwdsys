<?php
require_once("db.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? '';

$whereCategory = "";
$disabilityFilter = "";

/* =========================
   PWD / CWD FILTER
========================= */

if ($type === "pwdcwd") {
    if ($category === "PWD" || $category === "CWD") {
        $safeCategory = mysqli_real_escape_string($conn, $category);
        $whereCategory = "AND residents.resident_type = '$safeCategory'";
    }
}

/* =========================
   DISABILITY FILTER
========================= */

$allowedDisabilities = [
    "Cognitive",
    "Visual",
    "Physical",
    "Auditory",
    "Speech",
    "Psychosocial",
    "Others"
];

if (
    $type === "disability" &&
    in_array($category, $allowedDisabilities)
) {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $disabilityFilter = "AND resident_disabilities.disability_type = '$safeCategory'";
}

function getTitle($type, $category) {
    if ($type === "master") {
        return "Master List of Registered PWDs and CWDs";
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
    if (empty($birthdate)) {
        return "N/A";
    }

    try {
        return date_diff(
            date_create($birthdate),
            date_create("today")
        )->y;
    } catch (Exception $e) {
        return "N/A";
    }
}

function getResidentReportSql($extraWhere  = "") {
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
<title><?php echo getTitle($type, $category); ?></title>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 30px;
    color: #111;
}

.report-header {
    text-align: center;
    margin-bottom: 25px;
}

.report-header img {
    width: 70px;
    margin-bottom: 8px;
}

.report-header h2 {
    margin: 0;
    font-size: 18px;
    color: #b22222;
    text-transform: uppercase;
}

.report-header p {
    margin: 3px 0;
    font-size: 13px;
}

.report-title {
    text-align: center;
    margin: 25px 0 15px;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    border: 1px solid #333;
    padding: 7px;
    font-size: 12px;
    text-align: center;
}

th {
    background: #f1f1f1;
    font-weight: bold;
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
    font-family: Arial, sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

@page {
    margin: 10mm;
}

@media print {
    .print-btn {
        display: none;
    }

    body {
        padding: 10px;
        margin: 0;
    }

    th, td {
        font-size: 10px;
        padding: 5px;
    }
}
</style>
</head>

<body>

<div class="report-actions">
    <button class="print-btn" onclick="window.print()">Print Report</button>

    <a class="print-btn"
       href="processDownloadReportPDF.php?type=<?php echo htmlspecialchars($type); ?>&category=<?php echo htmlspecialchars($category); ?>">
        Download PDF
    </a>
</div>

<div class="report-header">
    <img src="../assets/barangay-logo.png">
    <h2>Samahan ng mga Persons with Disability ng Brgy. Sto. Niño QC</h2>
    <p>Area XXII, District IV</p>
    <p>50 San Isidro St., Quezon City</p>
</div>

<div class="report-title">
    <?php echo getTitle($type, $category); ?>
</div>

<?php if ($type === "master") : ?>

    <?php
    $sql = getResidentReportSql();
    $result = mysqli_query($conn, $sql);
    ?>

    <table>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Resident Type</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Guardian</th>
            <th>PWD ID No.</th>
            <th>Disability Type</th>
        </tr>

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
                <td><?php echo htmlspecialchars($fullName); ?></td>
                <td><?php echo htmlspecialchars($row['resident_type'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['sex'] ?? 'N/A')); ?></td>
                <td><?php echo htmlspecialchars(getAge($row['birthdate'] ?? null)); ?></td>
                <td><?php echo htmlspecialchars($row['birthdate'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['contact_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['guardian_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['pwdid_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disability_type'] ?? 'N/A'); ?></td>
            </tr>

        <?php endwhile; ?>
    </table>

<?php elseif ($type === "pwdcwd") : ?>

    <?php
    $sql = getResidentReportSql($whereCategory);
    $result = mysqli_query($conn, $sql);
    ?>

    <table>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Guardian</th>
            <th>PWD ID No.</th>
            <th>Disability Type</th>
            <th>Remarks</th>
        </tr>

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
                <td><?php echo htmlspecialchars($fullName); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['sex'] ?? 'N/A')); ?></td>
                <td><?php echo htmlspecialchars(getAge($row['birthdate'] ?? null)); ?></td>
                <td><?php echo htmlspecialchars($row['birthdate'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['contact_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['guardian_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['pwdid_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disability_type'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disability_remarks'] ?? 'N/A'); ?></td>
            </tr>

        <?php endwhile; ?>
    </table>

<?php elseif ($type === "disability") : ?>

    <?php
    $sql = getResidentReportSql($disabilityFilter);
    $result = mysqli_query($conn, $sql);
    ?>

    <table>
        <tr>
            <th>No.</th>
            <th>Full Name</th>
            <th>Resident Type</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Birthdate</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Guardian</th>
            <th>PWD ID No.</th>
            <th>Disability Type</th>
            <th>Remarks</th>
        </tr>

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
                <td><?php echo htmlspecialchars($fullName); ?></td>
                <td><?php echo htmlspecialchars($row['resident_type'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['sex'] ?? 'N/A')); ?></td>
                <td><?php echo htmlspecialchars(getAge($row['birthdate'] ?? null)); ?></td>
                <td><?php echo htmlspecialchars($row['birthdate'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['contact_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['guardian_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['pwdid_num'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disability_type'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disability_remarks'] ?? 'N/A'); ?></td>
            </tr>

        <?php endwhile; ?>
    </table>

<?php else : ?>

    <p style="text-align:center;">Invalid report type.</p>

<?php endif; ?>

</body>
</html>