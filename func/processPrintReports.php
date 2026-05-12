<?php
require_once("db.php");

$type = $_GET['type'] ?? '';
$category = $_GET['category'] ?? 'ALL';

$where = "";

if ($category === "PWD" || $category === "CWD") {
    $safeCategory = mysqli_real_escape_string($conn, $category);
    $where = "WHERE resident_type = '$safeCategory'";
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
        if ($category === "PWD") return "PWD Disability Classification Summary";
        if ($category === "CWD") return "CWD Disability Classification Summary";
        return "PWD/CWD Disability Classification Summary";
    }

    return "Report";
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
       href="processDownloadReportPDF.php?type=<?php echo $type; ?>&category=<?php echo $category; ?>">
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
    $sql = "
        SELECT *
        FROM residents
        ORDER BY resident_type, last_name, first_name
    ";

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
            <th>Status</th>
        </tr>

        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>

            <?php
            $fullName = $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name'];
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $fullName; ?></td>
                <td><?php echo $row['resident_type']; ?></td>
                <td><?php echo ucfirst($row['sex']); ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['birthdate']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact_num']; ?></td>
                <td><?php echo $row['guardian_name']; ?></td>
                <td><?php echo $row['pwdid_num']; ?></td>
                <td><?php echo $row['disablity_type']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>

        <?php endwhile; ?>
    </table>

<?php elseif ($type === "pwdcwd") : ?>

    <?php
    $sql = "
        SELECT *
        FROM residents
        $where
        ORDER BY resident_type, last_name, first_name
    ";

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
            <th>Status</th>
        </tr>

        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>

            <?php
            $fullName = $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name'];
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $fullName; ?></td>
                <td><?php echo ucfirst($row['sex']); ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['birthdate']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact_num']; ?></td>
                <td><?php echo $row['guardian_name']; ?></td>
                <td><?php echo $row['pwdid_num']; ?></td>
                <td><?php echo $row['disablity_type']; ?></td>
                <td><?php echo $row['disability_remarks']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>

        <?php endwhile; ?>
    </table>

<?php elseif ($type === "disability") : ?>

    <?php
    $sql = "
        SELECT 
            disablity_type,
            resident_type,
            COUNT(*) AS total
        FROM residents
        $where
        GROUP BY disablity_type, resident_type
        ORDER BY disablity_type, resident_type
    ";

    $result = mysqli_query($conn, $sql);
    ?>

    <table>
        <tr>
            <th>Disability Type</th>
            <th>Resident Type</th>
            <th>Total</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['disablity_type']; ?></td>
                <td><?php echo $row['resident_type']; ?></td>
                <td><?php echo $row['total']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

<?php else : ?>

    <p style="text-align:center;">Invalid report type.</p>

<?php endif; ?>

</body>
</html>