<?php
require_once "logic.php";

$results = [];
$history = loadHistoryFromFile();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['calculate'])) {
        // --- Perform Calculation ---
        $totalDistance = (float) $_POST['total_distance'];
        $distanceCovered = (float) $_POST['distance_covered'];
        $elapsedTime = (float) $_POST['elapsed_time']; // minutes
        $targetTime = (float) $_POST['target_time'];   // minutes

        $currentSpeed = calculateCurrentSpeed($distanceCovered, $elapsedTime);
        $requiredSpeed = calculateRequiredSpeed($totalDistance, $distanceCovered, $elapsedTime, $targetTime);

        $results = [
            "Total Distance" => $totalDistance . " km",
            "Distance Covered" => $distanceCovered . " km",
            "Elapsed Time" => $elapsedTime . " minutes",
            "Target Time" => $targetTime . " minutes",
            "Current Average Speed" => is_numeric($currentSpeed) ? round($currentSpeed, 2) . " km/h" : $currentSpeed,
            "Required Speed" => is_numeric($requiredSpeed) ? round($requiredSpeed, 2) . " km/h" : $requiredSpeed
        ];

        // Save new entry
        saveDataToFile([
            date("Y-m-d H:i:s"),
            $totalDistance,
            $distanceCovered,
            $elapsedTime,
            $targetTime,
            round($currentSpeed, 2),
            is_numeric($requiredSpeed) ? round($requiredSpeed, 2) : $requiredSpeed
        ]);

        $history = loadHistoryFromFile();
    } elseif (isset($_POST['clear'])) {
        // --- Clear History ---
        clearHistoryFile();
        $history = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathon Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>🏃 Marathon Progress Tracker</h2>
        <a href="index.php" class="back-btn">← Back to Home</a>

        <form method="POST" action="">
            <label>Total Marathon Distance (km):</label>
            <input type="number" step="0.01" name="total_distance" value="50" required>

            <label>Distance Already Covered (km):</label>
            <input type="number" step="0.01" name="distance_covered" required>

            <label>Elapsed Time (minutes):</label>
            <input type="number" step="0.01" name="elapsed_time" required>

            <label>Target Completion Time (minutes):</label>
            <input type="number" step="0.01" name="target_time" required>

            <button type="submit" name="calculate">Calculate</button>
        </form>

        <?php if (!empty($results)): ?>
            <div class="results">
                <h3>📊 Results</h3>
                <ul>
                    <?php foreach ($results as $key => $value): ?>
                        <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($history)): ?>
            <div class="history">
                <h3>📜 Historical Data</h3>
                <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <form method="POST" action="">
                        <button type="submit" name="clear" class="clear-btn">Clear History</button>
                    </form>
                    <form method="GET" action="download.php">
                        <button type="submit" class="download-btn">Download Data</button>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Dist (km)</th>
                            <th>Covered (km)</th>
                            <th>Elapsed (min)</th>
                            <th>Target (min)</th>
                            <th>Avg Speed (km/h)</th>
                            <th>Req Speed (km/h)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                            <tr>
                                <?php foreach ($row as $col): ?>
                                    <td><?php echo htmlspecialchars($col); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>