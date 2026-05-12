<?php
require_once("db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Residents_Full_Data.xls");

echo "
<table border='1'>

<tr>

<th>ID</th>
<th>First Name</th>
<th>Middle Name</th>
<th>Last Name</th>
<th>Civil Status</th>
<th>Birthdate</th>
<th>Age</th>
<th>Birthplace</th>
<th>Sex</th>
<th>Address</th>
<th>Contact Number</th>
<th>Emergency Contact</th>
<th>Emergency Contact Number</th>
<th>Emergency Contact Relationship</th>
<th>Socials</th>
<th>Disability Type</th>
<th>Disability Remarks</th>
<th>Resident Type</th>
<th>Guardian Name</th>
<th>Guardian Contact Number</th>
<th>Guardian Relationship</th>
<th>Father Name</th>
<th>Mother Name</th>
<th>Spouse Name</th>
<th>PWD ID Number</th>
<th>Control Number</th>
<th>ID Issue Date</th>
<th>ID Expiration Date</th>
<th>Status</th>

</tr>
";

$result =
    mysqli_query($conn,
    "SELECT * FROM residents");

while ($row = mysqli_fetch_assoc($result)) {

    echo "
    <tr>

    <td>{$row['ID']}</td>
    <td>{$row['first_name']}</td>
    <td>{$row['middle_name']}</td>
    <td>{$row['last_name']}</td>
    <td>{$row['civil_status']}</td>
    <td>{$row['birthdate']}</td>
    <td>{$row['age']}</td>
    <td>{$row['birthplace']}</td>
    <td>{$row['sex']}</td>
    <td>{$row['address']}</td>
    <td>{$row['contact_num']}</td>
    <td>{$row['emergency_cont']}</td>
    <td>{$row['emergency_cont_num']}</td>
    <td>{$row['emergency_cont_rel']}</td>
    <td>{$row['socials']}</td>
    <td>{$row['disablity_type']}</td>
    <td>{$row['disability_remarks']}</td>
    <td>{$row['resident_type']}</td>
    <td>{$row['guardian_name']}</td>
    <td>{$row['guardian_cont_num']}</td>
    <td>{$row['guardian_rel']}</td>
    <td>{$row['father_name']}</td>
    <td>{$row['mother_name']}</td>
    <td>{$row['spouse_name']}</td>
    <td>{$row['pwdid_num']}</td>
    <td>{$row['control_num']}</td>
    <td>{$row['idissue_date']}</td>
    <td>{$row['idexpiration_date']}</td>
    <td>{$row['status']}</td>

    </tr>
    ";
}

echo "</table>";
?>