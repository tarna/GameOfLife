<?php
require_once "database.php";

// Fetch analytics data
$totalSessions = 0;
$averageGenerations = 0;
$topUsers = [];

try {
    // Total sessions
    $stmt = $conn->query("SELECT COUNT(*) AS total FROM sessions");
    $totalSessions = $stmt->fetch()['total'];

    // Average generations
    $stmt = $conn->query("SELECT AVG(generations) AS average FROM sessions");
    $averageGenerations = round($stmt->fetch()['average'], 2);

    // Top 5 users by generations
    $stmt = $conn->query("
        SELECT u.name, u.email, SUM(s.generations) AS total_generations
        FROM sessions s
        JOIN users u ON s.user_id = u.id
        GROUP BY u.id
        ORDER BY total_generations DESC
        LIMIT 5
    ");
    $topUsers = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
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
            <td><?= $totalSessions ?></td>
            <td><?= $averageGenerations ?></td>
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
                <td><?= $user['total_generations'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button onclick="window.location.href='admin.php'">Back to Admin Panel</button>
</body>
</html>
