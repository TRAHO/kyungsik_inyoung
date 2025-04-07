<?php
require_once '../config/database.php';

// 페이지 번호 가져오기
$rsvp_page = isset($_GET['rsvp_page']) ? (int)$_GET['rsvp_page'] : 1;
$guestbook_page = isset($_GET['guestbook_page']) ? (int)$_GET['guestbook_page'] : 1;
$per_page = 5;

// 참석자 통계 조회
$stats = [
    'total' => 0,
    'attending' => 0,
    'not_attending' => 0,
    'groom_side' => 0,
    'bride_side' => 0
];

try {
    // 전체 참석자 수
    $stmt = $conn->query("SELECT COUNT(*) FROM rsvp");
    $stats['total'] = $stmt->fetchColumn();

    // 참석/불참 수
    $stmt = $conn->query("SELECT attendance, COUNT(*) FROM rsvp GROUP BY attendance");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['attendance'] === 'yes') {
            $stats['attending'] = $row['COUNT(*)'];
        } else {
            $stats['not_attending'] = $row['COUNT(*)'];
        }
    }

    // 신랑/신부 측 수
    $stmt = $conn->query("SELECT guest, COUNT(*) FROM rsvp GROUP BY guest");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['guest'] === 'groom') {
            $stats['groom_side'] = $row['COUNT(*)'];
        } else {
            $stats['bride_side'] = $row['COUNT(*)'];
        }
    }

    // 참석자 목록 조회 (페이징)
    $stmt = $conn->prepare("SELECT * FROM rsvp ORDER BY created_at DESC LIMIT :offset, :limit");
    $offset = ($rsvp_page - 1) * $per_page;
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    $rsvp_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 참석자 총 개수
    $stmt = $conn->query("SELECT COUNT(*) FROM rsvp");
    $total_rsvp = $stmt->fetchColumn();
    $total_rsvp_pages = ceil($total_rsvp / $per_page);

    // 방명록 조회 (페이징)
    $stmt = $conn->prepare("SELECT * FROM guestbook ORDER BY created_at DESC LIMIT :offset, :limit");
    $offset = ($guestbook_page - 1) * $per_page;
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->execute();
    $guestbook_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 방명록 총 개수
    $stmt = $conn->query("SELECT COUNT(*) FROM guestbook");
    $total_guestbook = $stmt->fetchColumn();
    $total_guestbook_pages = ceil($total_guestbook / $per_page);

} catch(PDOException $e) {
    $error = "데이터베이스 오류가 발생했습니다.";
}

// 페이지네이션 URL 생성 함수
function getPageUrl($page_type, $page) {
    $params = $_GET;
    $params[$page_type . '_page'] = $page;
    return '?' . http_build_query($params);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 대시보드</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: #f4fbf1;
            font-family: 'Cafe24Oneprettynight';
            padding: 15px;
            color: #333;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #43573a;
            font-size: 1.5rem;
            text-align: center;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .nav-links a {
            color: #58a23f;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #f0f5ee;
            transition: background-color 0.3s;
        }

        .nav-links a:hover {
            background-color: #e0ebd9;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card:first-child {
            grid-column: 1 / -1;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: #43573a;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .stat-card .number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #58a23f;
        }

        .rsvp-list {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .rsvp-list h2 {
            font-size: 1.2rem;
            color: #43573a;
            margin-bottom: 15px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 800px;
        }

        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }

        th:nth-child(1), td:nth-child(1) { min-width: 80px; } /* 이름 */
        th:nth-child(2), td:nth-child(2) { min-width: 60px; } /* 측 */
        th:nth-child(3), td:nth-child(3) { min-width: 60px; } /* 참석 여부 */
        th:nth-child(4), td:nth-child(4) { min-width: 60px; } /* 식사 여부 */
        th:nth-child(5), td:nth-child(5) { min-width: 100px; } /* 연락처 */
        th:nth-child(6), td:nth-child(6) { min-width: 80px; } /* 동행인 */
        th:nth-child(7), td:nth-child(7) { min-width: 120px; } /* 전달사항 */
        th:nth-child(8), td:nth-child(8) { min-width: 80px; } /* 작성일 */
        th:nth-child(9), td:nth-child(9) { min-width: 100px; } /* 관리 */

        th {
            background-color: #f0f5ee;
            color: #43573a;
            font-weight: normal;
        }

        .btn-action {
            background-color: #43573a;
            color: white;
            border: none;
            padding: 5px 8px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            margin-right: 3px;
            white-space: nowrap;
        }

        .btn-action:hover {
            background-color: #58a23f;
        }

        /* 모달 스타일 */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 24px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #43573a;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Cafe24Oneprettynight';
        }

        .btn-submit {
            background-color: #43573a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-family: 'Cafe24Oneprettynight';
        }

        .btn-submit:hover {
            background-color: #58a23f;
        }

        @media (min-width: 768px) {
            .stats {
                grid-template-columns: repeat(4, 1fr);
                gap: 15px;
            }

            .stat-card:first-child {
                grid-column: 1 / -1;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-card h3 {
                font-size: 1rem;
            }

            .stat-card .number {
                font-size: 1.8rem;
            }

            .rsvp-list {
                overflow-x: visible;
            }
            
            table {
                min-width: 100%;
            }
        }

        .guestbook-list {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .guestbook-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            position: relative;
        }

        .guestbook-item:last-child {
            border-bottom: none;
        }

        .guestbook-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .guestbook-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .guestbook-name {
            font-weight: bold;
            color: #43573a;
        }

        .guestbook-date {
            color: #666;
            font-size: 0.9rem;
        }

        .guestbook-message {
            color: #333;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination a, .pagination span {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #43573a;
        }

        .pagination a:hover {
            background-color: #f0f5ee;
        }

        .pagination .current {
            background-color: #43573a;
            color: white;
            border-color: #43573a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>관리자 대시보드</h1>
            <div class="nav-links">
                <a href="dashboard.php">대시보드</a>
                <a href="guest_snaps.php">게스트스냅 관리</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>전체 참석자</h3>
                <div class="number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>참석</h3>
                <div class="number"><?php echo $stats['attending']; ?></div>
            </div>
            <div class="stat-card">
                <h3>불참</h3>
                <div class="number"><?php echo $stats['not_attending']; ?></div>
            </div>
            <div class="stat-card">
                <h3>신랑 측</h3>
                <div class="number"><?php echo $stats['groom_side']; ?></div>
            </div>
            <div class="stat-card">
                <h3>신부 측</h3>
                <div class="number"><?php echo $stats['bride_side']; ?></div>
            </div>
        </div>

        <div class="rsvp-list">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>참석자 목록</h2>
                <a href="download_rsvp.php" class="btn-action" style="text-decoration: none;">엑셀 다운로드</a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>이름</th>
                            <th>측</th>
                            <th>참석 여부</th>
                            <th>식사 여부</th>
                            <th>연락처</th>
                            <th>동행인</th>
                            <th>전달사항</th>
                            <th>작성일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rsvp_list as $rsvp): ?>
                        <tr data-id="<?php echo $rsvp['id']; ?>">
                            <td><?php echo htmlspecialchars($rsvp['name1']); ?></td>
                            <td><?php echo $rsvp['guest'] === 'groom' ? '신랑' : '신부'; ?></td>
                            <td><?php echo $rsvp['attendance'] === 'yes' ? '참석' : '불참'; ?></td>
                            <td>
                                <?php
                                switch($rsvp['meal']) {
                                    case 'yes': echo 'O'; break;
                                    case 'no': echo 'X'; break;
                                    case 'none': echo '미정'; break;
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($rsvp['phone']); ?></td>
                            <td><?php echo htmlspecialchars($rsvp['name2'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($rsvp['message'] ?? ''); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($rsvp['created_at'])); ?></td>
                            <td>
                                <button class="btn-action" onclick="editRsvp(<?php echo $rsvp['id']; ?>)">수정</button>
                                <button class="btn-action" onclick="deleteRsvp(<?php echo $rsvp['id']; ?>)">삭제</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($total_rsvp_pages > 1): ?>
            <div class="pagination">
                <?php
                if ($total_rsvp_pages <= 5) {
                    $start_page = 1;
                    $end_page = $total_rsvp_pages;
                } else {
                    if ($rsvp_page <= 3) {
                        $start_page = 1;
                        $end_page = 5;
                    } elseif ($rsvp_page >= $total_rsvp_pages - 2) {
                        $start_page = $total_rsvp_pages - 4;
                        $end_page = $total_rsvp_pages;
                    } else {
                        $start_page = $rsvp_page - 2;
                        $end_page = $rsvp_page + 2;
                    }
                }
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <?php if ($i == $rsvp_page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo getPageUrl('rsvp', $i); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="guestbook-list">
            <h2>방명록</h2>
            <?php foreach ($guestbook_list as $guestbook): ?>
            <div class="guestbook-item">
                <div class="guestbook-actions">
                    <button class="btn-action" onclick="deleteGuestbook(<?php echo $guestbook['id']; ?>)">삭제</button>
                </div>
                <div class="guestbook-header">
                    <span class="guestbook-name"><?php echo htmlspecialchars($guestbook['name']); ?></span>
                    <span class="guestbook-date"><?php echo date('Y-m-d', strtotime($guestbook['created_at'])); ?></span>
                </div>
                <div class="guestbook-message"><?php echo nl2br(htmlspecialchars($guestbook['message'])); ?></div>
            </div>
            <?php endforeach; ?>
            
            <?php if ($total_guestbook_pages > 1): ?>
            <div class="pagination">
                <?php
                if ($total_guestbook_pages <= 5) {
                    $start_page = 1;
                    $end_page = $total_guestbook_pages;
                } else {
                    if ($guestbook_page <= 3) {
                        $start_page = 1;
                        $end_page = 5;
                    } elseif ($guestbook_page >= $total_guestbook_pages - 2) {
                        $start_page = $total_guestbook_pages - 4;
                        $end_page = $total_guestbook_pages;
                    } else {
                        $start_page = $guestbook_page - 2;
                        $end_page = $guestbook_page + 2;
                    }
                }
                
                for ($i = $start_page; $i <= $end_page; $i++):
                ?>
                    <?php if ($i == $guestbook_page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo getPageUrl('guestbook', $i); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 수정 모달 -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>참석 정보 수정</h2>
            <form id="editForm">
                <input type="hidden" id="editId" name="id">
                <div class="form-group">
                    <label for="editName1">이름</label>
                    <input type="text" id="editName1" name="name1" required>
                </div>
                <div class="form-group">
                    <label for="editGuest">측</label>
                    <select id="editGuest" name="guest" required>
                        <option value="groom">신랑 측</option>
                        <option value="bride">신부 측</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editAttendance">참석 여부</label>
                    <select id="editAttendance" name="attendance" required>
                        <option value="yes">참석</option>
                        <option value="no">불참</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editMeal">식사 여부</label>
                    <select id="editMeal" name="meal" required>
                        <option value="yes">O</option>
                        <option value="no">X</option>
                        <option value="none">미정</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editPhone">연락처</label>
                    <input type="tel" id="editPhone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="editName2">동행인</label>
                    <input type="text" id="editName2" name="name2">
                </div>
                <div class="form-group">
                    <label for="editMessage">전달사항</label>
                    <textarea id="editMessage" name="message"></textarea>
                </div>
                <button type="submit" class="btn-submit">수정하기</button>
            </form>
        </div>
    </div>

    <script>
    // 모달 관련 변수
    const modal = document.getElementById('editModal');
    const closeBtn = document.getElementsByClassName('close')[0];
    const editForm = document.getElementById('editForm');

    // 모달 닫기
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    // 모달 외부 클릭 시 닫기
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // 수정 폼 제출
    editForm.onsubmit = async function(e) {
        e.preventDefault();
        
        const formData = new FormData(editForm);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('update_rsvp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('수정이 완료되었습니다.');
                location.reload();
            } else {
                alert('수정 중 오류가 발생했습니다.');
            }
        } catch (error) {
            alert('수정 중 오류가 발생했습니다.');
        }
    }

    // 수정 버튼 클릭 시
    function editRsvp(id) {
        // 현재 행의 데이터를 가져와서 폼에 채우기
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            document.getElementById('editId').value = id;
            document.getElementById('editName1').value = row.cells[0].textContent;
            document.getElementById('editGuest').value = row.cells[1].textContent === '신랑' ? 'groom' : 'bride';
            document.getElementById('editAttendance').value = row.cells[2].textContent === '참석' ? 'yes' : 'no';
            document.getElementById('editMeal').value = row.cells[3].textContent === 'O' ? 'yes' : 
                                                      row.cells[3].textContent === 'X' ? 'no' : 'none';
            document.getElementById('editPhone').value = row.cells[4].textContent;
            document.getElementById('editName2').value = row.cells[5].textContent;
            document.getElementById('editMessage').value = row.cells[6].textContent;
            
            modal.style.display = 'block';
        }
    }

    // 삭제 버튼 클릭 시
    async function deleteRsvp(id) {
        if (confirm('정말 삭제하시겠습니까?')) {
            try {
                const response = await fetch('delete_rsvp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                });

                if (response.ok) {
                    alert('삭제가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('삭제 중 오류가 발생했습니다.');
                }
            } catch (error) {
                alert('삭제 중 오류가 발생했습니다.');
            }
        }
    }

    // 삭제 버튼 클릭 시
    async function deleteGuestbook(id) {
        if (confirm('정말 삭제하시겠습니까?')) {
            try {
                const response = await fetch('delete_guestbook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                });

                if (response.ok) {
                    alert('삭제가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('삭제 중 오류가 발생했습니다.');
                }
            } catch (error) {
                alert('삭제 중 오류가 발생했습니다.');
            }
        }
    }
    </script>
</body>
</html> 