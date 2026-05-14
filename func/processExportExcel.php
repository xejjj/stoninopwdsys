<?php
require_once("db.php");

function getAge($birthdate) {
    if (empty($birthdate)) return "N/A";

    try {
        return date_diff(date_create($birthdate), date_create("today"))->y;
    } catch (Exception $e) {
        return "N/A";
    }
}

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
</tr>
";

$sql = "
SELECT
    residents.*,

    resident_contacts.contact_num,
    resident_contacts.socials,

    resident_emergency_contacts.name AS emergency_name,
    resident_emergency_contacts.contact_num AS emergency_number,
    resident_emergency_contacts.relationship AS emergency_relation,

    GROUP_CONCAT(
        DISTINCT resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type,

    MAX(resident_disabilities.notes) AS disability_remarks,

    MAX(CASE WHEN resident_family_members.relationship = 'Father'
        THEN resident_family_members.name END) AS father_name,

    MAX(CASE WHEN resident_family_members.relationship = 'Mother'
        THEN resident_family_members.name END) AS mother_name,

    MAX(CASE WHEN resident_family_members.relationship = 'Spouse'
        THEN resident_family_members.name END) AS spouse_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father','Mother','Spouse')
        THEN resident_family_members.name
    END) AS guardian_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father','Mother','Spouse')
        THEN resident_family_members.relationship
    END) AS guardian_rel,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father','Mother','Spouse')
        THEN resident_family_members.contact_num
    END) AS guardian_cont_num

FROM residents

LEFT JOIN resident_contacts
ON residents.ID = resident_contacts.resident_id

LEFT JOIN resident_emergency_contacts
ON residents.ID = resident_emergency_contacts.resident_id

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id

LEFT JOIN resident_family_members
ON residents.ID = resident_family_members.resident_id

WHERE residents.record_status != 'archived'

GROUP BY residents.ID

ORDER BY residents.ID ASC
";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {

    echo "
    <tr>
    <td>" . htmlspecialchars($row['ID'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['first_name'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['middle_name'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['last_name'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['civil_status'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['birthdate'] ?? '') . "</td>
    <td>" . htmlspecialchars(getAge($row['birthdate'] ?? null)) . "</td>
    <td>" . htmlspecialchars($row['birthplace'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['sex'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['address'] ?? '') . "</td>
    <td style='mso-number-format:\"\\@\";'>" . htmlspecialchars($row['contact_num'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['emergency_name'] ?? '') . "</td>
    <td style='mso-number-format:\"\\@\";'>" . htmlspecialchars($row['emergency_number'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['emergency_relation'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['socials'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['disability_type'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['disability_remarks'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['resident_type'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['guardian_name'] ?? '') . "</td>
    <td style='mso-number-format:\"\\@\";'>" . htmlspecialchars($row['guardian_cont_num'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['guardian_rel'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['father_name'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['mother_name'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['spouse_name'] ?? '') . "</td>
    <td style='mso-number-format:\"\\@\";'>" . htmlspecialchars($row['pwdid_num'] ?? '') . "</td>
    <td style='mso-number-format:\"\\@\";'>" . htmlspecialchars($row['control_num'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['idissue_date'] ?? '') . "</td>
    <td>" . htmlspecialchars($row['idexpiration_date'] ?? '') . "</td>
    </tr>
    ";
}

echo "</table>";
?>