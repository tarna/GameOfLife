<?php
// Connect to the database
require_once "database.php";

// Initialize variables
$totalSessions = 0;
$averageGenerations = 0;
$topUsers = [];

try {
    // Total sessions
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total FROM SESSIONS");
    $stmt1->execute();
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    $totalSessions = $result1 ? $result1['total'] : 0;

    // Average generations
    $stmt2 = $conn->prepare("SELECT AVG(generations) AS average FROM SESSIONS");
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $averageGenerations = $result2 ? round($result2['average'], 2) : 0;

    // Top 5 users by total generations
    $stmt3 = $conn->prepare("
        SELECT u.name, u.email, SUM(s.generations) AS total_generations
        FROM SESSIONS s
        JOIN users u ON s.user_id = u.id
        GROUP BY u.id, u.name, u.email
        ORDER BY total_generations DESC
        LIMIT 5
    ");
    $stmt3->execute();
    $topUsers = $stmt3->fetchAll(PDO::FETCH_ASSOC);

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
