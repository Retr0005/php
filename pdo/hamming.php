<?php
require 'vendor/autoload.php';

$templates = new League\Plates\Engine('templates', 'tpl');

// Initialize variables to store input and result
$input1 = '';
$input2 = '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input1 = $_POST['input1'];
    $input2 = $_POST['input2'];

    // Check if the inputs contain only 'C', 'A', 'G', and 'T'
    if (isValidDNAString($input1) && isValidDNAString($input2)) {
        $result = calculateHammingDistance($input1, $input2);
    } else {
        $result = 'Error: DNA strings must contain only "C", "A", "G", and "T".';
    }
}

echo $templates->render('form', [
    'input1' => $input1,
    'input2' => $input2,
    'result' => $result,
]);

/**
 * Check if a given DNA string contains only valid characters ('C', 'A', 'G', and 'T').
 *
 * @param string $str The DNA string to check.
 * @return bool True if the string is valid; otherwise, false.
 */
function isValidDNAString($str) {
    return preg_match('/^[CAGT]+$/', $str);
}

/**
 * Calculate the Hamming distance between two DNA strings.
 *
 * @param string $str1 The first DNA string.
 * @param string $str2 The second DNA string.
 * @return int The Hamming distance between the two strings.
 */
function calculateHammingDistance($str1, $str2) {
    $length = strlen($str1);
    $distance = 0;

    for ($i = 0; $i < $length; $i++) {
        if ($str1[$i] !== $str2[$i]) {
            $distance++;
        }
    }

    return $distance;
}
