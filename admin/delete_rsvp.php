<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $conn->prepare("DELETE FROM rsvp WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);

    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 