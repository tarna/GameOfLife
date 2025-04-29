<?php
require 'database.php';

$conn = connectToDatabase();

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