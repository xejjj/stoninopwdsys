<?php
session_start();
require_once("func/db.php");

// Determine which report to generate
$type = $_GET['type'] ?? 'master';
$report_title = "Resident Report";
$date_generated = date('F j, Y, g:i a');

// Fetch data based on report type
$data = [];
if ($type === 'master') {
    $report_title = "Master List of Registered PWDs and CWDs";
    $sql = "SELECT first_name, middle_name, last_name, pwdid_num, disablity_type, resident_type, sex, status FROM residents ORDER BY last_name ASC";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
} elseif ($type === 'summary') {
    $report_title = "PWD/CWD Summary by Age Group";
    // Count age brackets
    $sql = "SELECT dob, resident_type FROM residents";
    $result = mysqli_query($conn, $sql);
    $summary = [
        'Minors (0-17)' => ['PWD' => 0, 'CWD' => 0, 'Total' => 0],
        'Adults (18-59)' => ['PWD' => 0, 'CWD' => 0, 'Total' => 0],
        'Seniors (60+)' => ['PWD' => 0, 'CWD' => 0, 'Total' => 0]
    ];
    
    $now = new DateTime();
    while ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['dob'])) {
            try {
                $dob = new DateTime($row['dob']);
                $age = $now->diff($dob)->y;
                $cat = $row['resident_type'] === 'CWD' ? 'CWD' : 'PWD';
                
                if ($age <= 17) {
                    $summary['Minors (0-17)'][$cat]++;
                    $summary['Minors (0-17)']['Total']++;
                } elseif ($age <= 59) {
                    $summary['Adults (18-59)'][$cat]++;
                    $summary['Adults (18-59)']['Total']++;
                } else {
                    $summary['Seniors (60+)'][$cat]++;
                    $summary['Seniors (60+)']['Total']++;
                }
            } catch (Exception $e) { }
        }
    }
} elseif ($type === 'classification') {
    $report_title = "Disability Classification Summary";
    // Count individual disability types
    $sql = "SELECT disablity_type FROM residents";
    $result = mysqli_query($conn, $sql);
    $classifications = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['disablity_type'])) {
            $types = explode(',', $row['disablity_type']);
            foreach ($types as $t) {
                $t = trim($t);
                if (!empty($t)) {
                    if (!isset($classifications[$t])) {
                        $classifications[$t] = 0;
                    }
                    $classifications[$t]++;
                }
            }
        }
    }
    arsort($classifications); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print - <?php echo $report_title; ?></title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        color: #000;
        margin: 0;
        padding: 20px;
        background: #fff;
    }
    .header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
    .header h1 {
        margin: 0 0 5px 0;
        font-size: 24px;
        text-transform: uppercase;
    }
    .header p {
        margin: 0;
        font-size: 14px;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 14px;
    }
    table, th, td {
        border: 1px solid #000;
    }
    th {
        background-color: #f2f2f2;
        padding: 10px;
        text-align: left;
    }
    td {
        padding: 8px 10px;
    }
    .text-center { text-align: center; }
    
    @media print {
        @page { margin: 0.5in; }
        body { padding: 0; }
        .no-print { display: none; }
    }
</style>
</head>
<body>

<div class="header">
    <h1>Brgy. Sto. Niño PWD/CWD System</h1>
    <p><strong><?php echo $report_title; ?></strong></p>
    <p>Generated on: <?php echo $date_generated; ?></p>
</div>

<?php if ($type === 'master'): ?>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>ID Number</th>
                <th>Disability Type</th>
                <th>Category</th>
                <th>Sex</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): 
                $full_name = htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']);
            ?>
            <tr>
                <td><?php echo $full_name; ?></td>
                <td><?php echo htmlspecialchars($row['pwdid_num'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['disablity_type'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['resident_type'] ?: 'N/A'); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['sex'] ?: 'N/A')); ?></td>
                <td><?php echo htmlspecialchars($row['status'] ?: 'N/A'); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($data)): ?>
            <tr><td colspan="6" class="text-center">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

<?php elseif ($type === 'summary'): ?>
    <table>
        <thead>
            <tr>
                <th>Age Group</th>
                <th class="text-center">PWD Count</th>
                <th class="text-center">CWD Count</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_pwd = 0; $total_cwd = 0; $grand_total = 0;
            foreach ($summary as $group => $counts): 
                $total_pwd += $counts['PWD'];
                $total_cwd += $counts['CWD'];
                $grand_total += $counts['Total'];
            ?>
            <tr>
                <td><strong><?php echo $group; ?></strong></td>
                <td class="text-center"><?php echo $counts['PWD']; ?></td>
                <td class="text-center"><?php echo $counts['CWD']; ?></td>
                <td class="text-center"><strong><?php echo $counts['Total']; ?></strong></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Grand Total</strong></td>
                <td class="text-center"><strong><?php echo $total_pwd; ?></strong></td>
                <td class="text-center"><strong><?php echo $total_cwd; ?></strong></td>
                <td class="text-center"><strong><?php echo $grand_total; ?></strong></td>
            </tr>
        </tbody>
    </table>

<?php elseif ($type === 'classification'): ?>
    <table>
        <thead>
            <tr>
                <th>Disability Classification</th>
                <th class="text-center">Total Residents</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($classifications as $type => $count): 
                $total += $count;
            ?>
            <tr>
                <td><?php echo htmlspecialchars(ucfirst($type)); ?></td>
                <td class="text-center"><?php echo $count; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($classifications)): ?>
            <tr><td colspan="2" class="text-center">No data available.</td></tr>
            <?php else: ?>
            <tr>
                <td><strong>Total Classifications Logged</strong></td>
                <td class="text-center"><strong><?php echo $total; ?></strong></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    // Automatically open the print dialog once the page loads
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>