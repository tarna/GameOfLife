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
    echo "Could not connect to server\n";
    die("Connection failed: " . $conn->connect_error);
}

function connectToDatabase() {
    global $conn;
    return $conn;
}