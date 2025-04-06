<?php
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 입력 데이터 검증
        $guest = $_POST['guest'] ?? '';
        $attendance = $_POST['attend'] ?? '';
        $meal = $_POST['meal'] ?? '';
        $name1 = trim($_POST['name1'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $name2 = trim($_POST['name2'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $password = password_hash('1234', PASSWORD_DEFAULT); // 임시 비밀번호

        // 필수 입력값 검증
        if (empty($guest) || empty($attendance) || empty($meal) || empty($name1) || empty($phone)) {
            throw new Exception('필수 입력값을 모두 입력해주세요.');
        }

        // 데이터베이스에 저장
        $stmt = $conn->prepare("
            INSERT INTO rsvp (guest, attendance, meal, name1, phone, name2, message, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([$guest, $attendance, $meal, $name1, $phone, $name2, $message, $password]);

        echo json_encode([
            'success' => true,
            'message' => '참석 여부가 성공적으로 전달되었습니다.'
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => '잘못된 요청입니다.'
    ]);
}
?> 