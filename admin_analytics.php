<?php
require_once "database.php";

$conn = connectToDatabase();

$totalSessions = getTotalSessions();
$averageGenerations = getAverageGenerations();
$topUsers = getTopUsers(5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Analytics Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Game Analytics Dashboard</h1>

    <table>
        <tr>
            <th>Total Sessions</th>
            <th>Average Generations per Session</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($totalSessions) ?></td>
            <td><?= htmlspecialchars($averageGenerations) ?></td>
        </tr>
    </table>

    <h2>Top 5 Users by Generations</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Total Generations</th>
        </tr>
        <?php foreach ($topUsers as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['total_generations']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button onclick="window.location.href='admin.php'">Back to Admin Panel</button>
</body>
</html>