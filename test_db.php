<?php
require_once 'config/database.php';

try {
    // 간단한 쿼리 실행
    $stmt = $conn->query("SELECT 1");
    echo "데이터베이스 연결 성공!";
} catch(PDOException $e) {
    echo "오류 발생: " . $e->getMessage();
}
?> 