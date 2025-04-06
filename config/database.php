<?php
// 데이터베이스 연결 설정
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'KI_wedding_db');

define('DB_HOST', 'localhost');
define('DB_USER', 'ki_wedding');
define('DB_PASS', 'ki_wedding_0524@');
define('DB_NAME', 'ki_wedding_db');

// 데이터베이스 연결
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> 