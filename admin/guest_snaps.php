<?php
require_once '../config/database.php';
session_start();

// 이미지 저장 디렉토리 확인 및 생성
$image_dir = '../asset/img/snap';
if (!file_exists($image_dir)) {
    if (!mkdir($image_dir, 0777, true)) {
        die('이미지 저장 디렉토리를 생성할 수 없습니다.');
    }
}

// 테이블 존재 여부 확인 및 생성
try {
    $stmt = $conn->query("SHOW TABLES LIKE 'guest_snaps'");
    if ($stmt->rowCount() == 0) {
        $sql = file_get_contents('create_guest_snaps_table.sql');
        $conn->exec($sql);
    }
} catch(PDOException $e) {
    die("테이블 생성 중 오류가 발생했습니다: " . $e->getMessage());
}

// 게스트스냅 이미지 목록 가져오기
$stmt = $conn->prepare("SELECT * FROM guest_snaps ORDER BY created_at DESC");
$stmt->execute();
$snaps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게스트스냅 관리</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .snap-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .snap-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .snap-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .snap-info {
            padding: 15px;
        }
        .snap-info p {
            margin: 5px 0;
            color: #666;
        }
        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }
        .empty-message {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>게스트스냅 관리</h1>
        <div class="snap-grid">
            <?php if (empty($snaps)): ?>
                <div class="empty-message">
                    아직 업로드된 게스트스냅이 없습니다.
                </div>
            <?php else: ?>
                <?php foreach ($snaps as $snap): ?>
                    <div class="snap-item">
                        <img src="/<?php echo htmlspecialchars($snap['image_path']); ?>" alt="게스트스냅">
                        <div class="snap-info">
                            <p>업로드자: <?php echo htmlspecialchars($snap['guest_name']); ?></p>
                            <p>날짜: <?php echo date('Y-m-d H:i', strtotime($snap['created_at'])); ?></p>
                            <button class="delete-btn" onclick="deleteSnap(<?php echo $snap['id']; ?>)">삭제</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function deleteSnap(id) {
            if (confirm('정말 삭제하시겠습니까?')) {
                fetch('delete_guest_snap.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('삭제 중 오류가 발생했습니다.');
                    }
                });
            }
        }
    </script>
</body>
</html> 