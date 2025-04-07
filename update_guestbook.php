<?php
require_once 'config/database.php';

header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

try {
    // 비밀번호 확인
    $stmt = $conn->prepare("SELECT password FROM guestbook WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $stored_password = $stmt->fetchColumn();
    
    if (!password_verify($data['old_password'], $stored_password)) {
        throw new Exception('비밀번호가 일치하지 않습니다.');
    }
    
    // 방명록 수정
    $stmt = $conn->prepare("UPDATE guestbook SET name = :name, message = :message, password = :password WHERE id = :id");
    $stmt->execute([
        ':name' => $data['name'],
        ':message' => $data['message'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ':id' => $data['id']
    ]);
    
    // 수정된 방명록 정보 가져오기
    $stmt = $conn->prepare("SELECT * FROM guestbook WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $updated_guestbook = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 날짜 형식 변환
    $updated_guestbook['created_at'] = date('Y-m-d', strtotime($updated_guestbook['created_at']));

    echo json_encode([
        'success' => true,
        'message' => '방명록이 수정되었습니다.',
        'data' => $updated_guestbook
    ]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 