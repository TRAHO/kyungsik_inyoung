<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>참석 여부 전달</title>
    <style>
        .rsvp-form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #43573a;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #58a23f;
        }
    </style>
</head>
<body>
    <div class="rsvp-form">
        <h2>참석 여부 전달</h2>
        <form action="process_rsvp.php" method="POST">
            <div class="form-group">
                <label for="name">이름</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="attendance">참석 여부</label>
                <select id="attendance" name="attendance" required>
                    <option value="참석">참석</option>
                    <option value="불참">불참</option>
                    <option value="미정">미정</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="guest_count">동반 인원 수</label>
                <input type="number" id="guest_count" name="guest_count" min="1" value="1" required>
            </div>
            
            <div class="form-group">
                <label for="message">전하고 싶은 말</label>
                <textarea id="message" name="message" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label for="password">비밀번호 (수정/삭제시 필요)</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">전송</button>
        </form>
    </div>
</body>
</html> 