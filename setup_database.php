<?php
require_once 'config/database.php';

try {
    // SQL 파일 읽기
    $sql = file_get_contents('sql/create_tables.sql');
    
    // SQL 실행
    $conn->exec($sql);
    
    echo "테이블 생성이 완료되었습니다!";
    
} catch(PDOException $e) {
    echo "오류 발생: " . $e->getMessage();
}
?> 