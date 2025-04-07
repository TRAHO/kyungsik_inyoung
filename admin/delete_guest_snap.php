<?php
require_once '../config/database.php';
session_start();

header('Content-Type: application/json');

// POST 데이터 받기
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
    exit;
}

try {
    // 이미지 정보 가져오기
    $stmt = $conn->prepare("SELECT image_path FROM guest_snaps WHERE id = :id");
    $stmt->execute([':id' => $data['id']]);
    $image_path = $stmt->fetchColumn();

    if ($image_path) {
        // 실제 파일 삭제
        $full_path = '../' . $image_path;
        if (file_exists($full_path)) {
            unlink($full_path);
        }

        // 데이터베이스에서 레코드 삭제
        $stmt = $conn->prepare("DELETE FROM guest_snaps WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);

        echo json_encode(['success' => true, 'message' => '이미지가 삭제되었습니다.']);
    } else {
        throw new Exception('이미지를 찾을 수 없습니다.');
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 