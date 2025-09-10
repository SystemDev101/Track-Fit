<?php
// ---------------- FUNCTIONS ---------------- //

// Calculate current average speed (km/h)
function calculateCurrentSpeed($distanceCovered, $elapsedTime)
{
    if ($elapsedTime == 0) return 0;
    return $distanceCovered / ($elapsedTime / 60); // elapsedTime in minutes -> hours
}

// Calculate required speed (km/h) to finish in target time
function calculateRequiredSpeed($totalDistance, $distanceCovered, $elapsedTime, $targetTime)
{
    $remainingDistance = $totalDistance - $distanceCovered;
    $remainingTime = $targetTime - $elapsedTime; // minutes

    if ($remainingTime <= 0) return "Impossible";
    return $remainingDistance / ($remainingTime / 60); // hours
}

// Save calculation results to file
function saveDataToFile($data)
{
    $file = "data.txt";
    $entry = implode(" | ", $data) . "\n";
    file_put_contents($file, $entry, FILE_APPEND);
}

// Load historical runs from file
function loadHistoryFromFile()
{
    $file = "data.txt";
    $history = [];
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $parts = explode(" | ", $line);
            $history[] = $parts;
        }
    }
    return $history;
}

// Clear all data
function clearHistoryFile()
{
    $file = "data.txt";
    if (file_exists($file)) {
        file_put_contents($file, ""); // overwrite with empty
    }
}
