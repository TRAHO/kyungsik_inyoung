<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT * FROM guestbook ORDER BY created_at DESC");
    $guestbook_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 날짜 형식 변환
    foreach ($guestbook_list as &$guestbook) {
        $guestbook['created_at'] = date('Y-m-d', strtotime($guestbook['created_at']));
    }

    echo json_encode($guestbook_list);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 