<?php
// func/reportFunctions.php

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
        return date_diff(date_create($birthdate), date_create("today"))->y;
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
    return date('Y/m/d', strtotime($trimVal));
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
?>