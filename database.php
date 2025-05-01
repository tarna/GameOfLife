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

function createAllTables() {
    createUsersTable();
    createSessionsTable();
}

function createUsersTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS USERS (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(100) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user'
    )";

    $conn->query($sql);
}

function createSessionsTable() {
    global $conn;
    $sql = "CREATE TABLE IF NOT EXISTS SESSIONS (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        generations INT(6) UNSIGNED DEFAULT 0,
        start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        end_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE
    )";

    $conn->query($sql);
}

function getUser() {
    $id = $_COOKIE['id'];
    global $conn;
    $sql = "SELECT * FROM USERS WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function startSession($userId) {
    global $conn;

    // Insert a new session for the user
    $stmt = $conn->prepare("INSERT INTO SESSIONS (user_id, generations) VALUES (?, 0)");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $sessionId = $stmt->insert_id;
    $stmt->close();

    return $sessionId;
}

function updateGenerations($sessionId, $newGenerations) {
    global $conn;

    // Update the generations count for the current session
    $stmt = $conn->prepare("UPDATE SESSIONS SET generations = ? WHERE id = ?");
    $stmt->bind_param("ii", $newGenerations, $sessionId);
    $stmt->execute();
    $stmt->close();
}

function endSession($sessionId) {
    global $conn;

    // Update the end_time to mark the session as ended
    $stmt = $conn->prepare("UPDATE SESSIONS SET end_time = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $sessionId);
    $stmt->execute();
    $stmt->close();
}