<?php
require 'config.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$dbname = $config['dbname'];

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM USERS WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $conn->error;
    }

    header("Location: admin.php");
    exit();
} else {
    echo "Invalid request.";
}

$conn->close();