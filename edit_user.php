<?php
require 'database.php';

// Create connection
$conn = connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "UPDATE USERS SET name='$name', email='$email', role='$role' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully.";
    } else {
        echo "Error updating user: " . $conn->error;
    }

    header("Location: admin.php");
    exit();
}

$conn->close();