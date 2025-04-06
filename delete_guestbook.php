<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $id = $_POST['id'];
    $password = $_POST['password'];
    
    // 비밀번호 확인
    $stmt = $conn->prepare("SELECT password FROM guestbook WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $stored_password = $stmt->fetchColumn();
    
    if (!$stored_password) {
        throw new Exception('방명록이 존재하지 않습니다.');
    }
    
    if (!password_verify($password, $stored_password)) {
        throw new Exception('비밀번호가 일치하지 않습니다.');
    }
    
    // 방명록 삭제
    $stmt = $conn->prepare("DELETE FROM guestbook WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    echo json_encode([
        'success' => true,
        'message' => '방명록이 삭제되었습니다.'
    ]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 