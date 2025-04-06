<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $conn->prepare("UPDATE rsvp SET 
        name1 = :name1,
        guest = :guest,
        attendance = :attendance,
        meal = :meal,
        phone = :phone,
        name2 = :name2,
        message = :message
        WHERE id = :id");

    $stmt->execute([
        ':name1' => $data['name1'],
        ':guest' => $data['guest'],
        ':attendance' => $data['attendance'],
        ':meal' => $data['meal'],
        ':phone' => $data['phone'],
        ':name2' => $data['name2'] ?? null,
        ':message' => $data['message'] ?? null,
        ':id' => $data['id']
    ]);

    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 