<?php
require 'database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['action'])) {
    echo json_encode(['error' => 'No action specified']);
    exit;
}

$action = $data['action'];

if ($action === 'startSession') {
    $userId = $data['userId'];
    $sessionId = startSession($userId);
    echo json_encode(['sessionId' => $sessionId]);
    exit;
} elseif ($action === 'updateGenerations') {
    $sessionId = $data['sessionId'];
    $newGenerations = $data['newGenerations'];
    updateGenerations($sessionId, $newGenerations);
    echo json_encode(['status' => 'success']);
    exit;
} elseif ($action === 'endSession') {
    $sessionId = $data['sessionId'];
    endSession($sessionId);
    echo json_encode(['status' => 'success']);
    exit;
} else {
    echo json_encode(['error' => 'Invalid action']);
    exit;
}