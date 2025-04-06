<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';

// 모든 참석자 데이터 조회
try {
    $stmt = $conn->query("SELECT * FROM rsvp ORDER BY created_at DESC");
    $rsvp_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("데이터베이스 오류가 발생했습니다: " . $e->getMessage());
}

// 엑셀 헤더 설정
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=참석자_목록_".date("Ymd_His").".xls");
header("Content-Description: PHP Generated Data");
header("Pragma: no-cache");
header("Expires: 0");

// HTML 문서 시작
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
echo "</head>";
echo "<body>";

// 테이블 시작
echo "<table border='1'>";
echo "<tr>";
echo "<th>이름</th>";
echo "<th>측</th>";
echo "<th>참석 여부</th>";
echo "<th>식사 여부</th>";
echo "<th>연락처</th>";
echo "<th>동행인</th>";
echo "<th>전달사항</th>";
echo "<th>작성일</th>";
echo "</tr>";

// 데이터 출력
foreach ($rsvp_list as $rsvp) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($rsvp['name1']) . "</td>";
    echo "<td>" . ($rsvp['guest'] === 'groom' ? '신랑' : '신부') . "</td>";
    echo "<td>" . ($rsvp['attendance'] === 'yes' ? '참석' : '불참') . "</td>";
    echo "<td>";
    switch($rsvp['meal']) {
        case 'yes': echo 'O'; break;
        case 'no': echo 'X'; break;
        case 'none': echo '미정'; break;
    }
    echo "</td>";
    echo "<td>" . htmlspecialchars($rsvp['phone']) . "</td>";
    echo "<td>" . htmlspecialchars($rsvp['name2'] ?? '') . "</td>";
    echo "<td>" . htmlspecialchars($rsvp['message'] ?? '') . "</td>";
    echo "<td>" . date('Y-m-d', strtotime($rsvp['created_at'])) . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "</body>";
echo "</html>";
?> 