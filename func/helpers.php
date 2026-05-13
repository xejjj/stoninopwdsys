<?php
// func/helpers.php

/**
 * Calculates age and formats the date of birth string.
 * Returns "N/A" if the date is 0000-00-00 or empty.
 */
function getFormattedAge($dob_string) {
    if (empty($dob_string) || $dob_string === '0000-00-00') {
        return 'N/A';
    }
    
    try {
        $dob = new DateTime($dob_string);
        $now = new DateTime();
        $age = $now->diff($dob)->y;
        $dob_formatted = $dob->format('F j, Y');
        
        return "$dob_formatted ($age years old)";
    } catch (Exception $e) {
        return "Invalid Date";
    }
}

/**
 * Helper to color-code disability badges
 */
function badgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"     => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
    ];
    $key = strtolower(trim(explode(",", $type)[0]));
    return $map[$key] ?? "badge-physical";
}
?>