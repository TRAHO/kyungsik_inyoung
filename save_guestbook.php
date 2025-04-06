<?php
require_once 'config/database.php';

header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $conn->prepare("INSERT INTO guestbook (name, message, password) VALUES (:name, :message, :password)");
    $stmt->execute([
        ':name' => $data['name'],
        ':message' => $data['message'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
    ]);

    // 저장된 방명록 정보 가져오기
    $last_id = $conn->lastInsertId();
    $stmt = $conn->prepare("SELECT * FROM guestbook WHERE id = :id");
    $stmt->execute([':id' => $last_id]);
    $saved_guestbook = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 날짜 형식 변환
    $saved_guestbook['created_at'] = date('Y-m-d', strtotime($saved_guestbook['created_at']));

    echo json_encode([
        'success' => true,
        'message' => '방명록이 등록되었습니다.',
        'data' => $saved_guestbook
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => '방명록 등록에 실패했습니다.',
        'error' => $e->getMessage()
    ]);
} 