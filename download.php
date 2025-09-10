<?php
$file = "data.txt";

if (!file_exists($file) || filesize($file) == 0) {
    die("No data available to download.");
}

// Read file and convert to CSV output
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="marathon_data.csv"');

$output = fopen("php://output", "w");

// Add header row
fputcsv($output, ["Date", "Total Distance (km)", "Covered (km)", "Elapsed (min)", "Target (min)", "Avg Speed (km/h)", "Req Speed (km/h)"]);

// Read lines and output as CSV
$lines = file($file, FILE_IGNORE_NEW_LINES);
foreach ($lines as $line) {
    $parts = explode(" | ", $line);
    fputcsv($output, $parts);
}

fclose($output);
exit();
