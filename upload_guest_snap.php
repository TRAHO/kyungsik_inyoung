<?php
// 오류 로깅 활성화
error_reporting(E_ALL);
ini_set('display_errors', 0); // 브라우저에 오류 표시 비활성화
ini_set('log_errors', 1); // 오류 로깅 활성화
ini_set('error_log', 'php_errors.log'); // 오류 로그 파일 지정

// JSON 응답 헤더 설정
header('Content-Type: application/json; charset=utf-8');

// 업로드 디렉토리 설정
$uploadDir = 'asset/img/snap/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        error_log("디렉토리 생성 실패: " . $uploadDir);
        echo json_encode(['success' => false, 'message' => '업로드 디렉토리 생성 실패']);
        exit;
    }
}

// 디렉토리 쓰기 권한 확인
if (!is_writable($uploadDir)) {
    error_log("디렉토리 쓰기 권한 없음: " . $uploadDir);
    echo json_encode(['success' => false, 'message' => '업로드 디렉토리에 쓰기 권한이 없습니다.']);
    exit;
}

// GD 라이브러리 확인
if (!extension_loaded('gd')) {
    error_log("GD 라이브러리가 설치되어 있지 않습니다.");
    echo json_encode(['success' => false, 'message' => '서버에 GD 라이브러리가 설치되어 있지 않습니다.']);
    exit;
}

// 파일 업로드 처리
if (isset($_FILES['images'])) {
    $files = $_FILES['images'];
    $uploadedFiles = [];
    $errors = [];

    // 여러 파일 처리
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            // 파일 타입 검사
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = $file['name'] . ': 허용되지 않는 파일 형식입니다. (JPEG, PNG, GIF만 가능)';
                continue;
            }

            // 파일 크기 검사 (10MB 제한)
            if ($file['size'] > 10 * 1024 * 1024) {
                $errors[] = $file['name'] . ': 파일 크기가 너무 큽니다. (최대 10MB)';
                continue;
            }

            $fileName = uniqid() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;

            // 이미지 타입 확인
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                $errors[] = $file['name'] . ': 유효하지 않은 이미지 파일입니다.';
                continue;
            }

            try {
                // 이미지 리사이징
                $sourceImage = imagecreatefromstring(file_get_contents($file['tmp_name']));
                if (!$sourceImage) {
                    throw new Exception('이미지 생성 실패');
                }

                $width = imagesx($sourceImage);
                $height = imagesy($sourceImage);

                // 최대 크기 설정
                $maxWidth = 768;
                $maxHeight = 1024;

                if ($width > $maxWidth || $height > $maxHeight) {
                    // 비율 계산
                    $ratio = min($maxWidth / $width, $maxHeight / $height);
                    $newWidth = floor($width * $ratio);
                    $newHeight = floor($height * $ratio);
                    
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    if (!$newImage) {
                        throw new Exception('새 이미지 생성 실패');
                    }

                    // 투명도 유지 (PNG, GIF)
                    if ($file['type'] === 'image/png' || $file['type'] === 'image/gif') {
                        imagealphablending($newImage, false);
                        imagesavealpha($newImage, true);
                        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    }

                    if (!imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
                        throw new Exception('이미지 리사이징 실패');
                    }
                    
                    // 이미지 저장
                    $success = false;
                    switch ($file['type']) {
                        case 'image/jpeg':
                            $success = imagejpeg($newImage, $targetPath, 90);
                            break;
                        case 'image/png':
                            $success = imagepng($newImage, $targetPath, 9);
                            break;
                        case 'image/gif':
                            $success = imagegif($newImage, $targetPath);
                            break;
                    }

                    if (!$success) {
                        throw new Exception('이미지 저장 실패');
                    }

                    imagedestroy($newImage);
                } else {
                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        throw new Exception('파일 이동 실패');
                    }
                }

                imagedestroy($sourceImage);
                $uploadedFiles[] = $targetPath;
            } catch (Exception $e) {
                error_log("이미지 처리 오류: " . $e->getMessage());
                $errors[] = $file['name'] . ': ' . $e->getMessage();
                if (isset($newImage)) {
                    imagedestroy($newImage);
                }
                if (isset($sourceImage)) {
                    imagedestroy($sourceImage);
                }
            }
        } else {
            $errorMessage = '업로드 실패 (오류 코드: ' . $files['error'][$i] . ')';
            error_log("파일 업로드 오류: " . $errorMessage);
            $errors[] = $files['name'][$i] . ': ' . $errorMessage;
        }
    }

    if (count($errors) > 0) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'imagePaths' => $uploadedFiles
        ]);
    }
} else {
    error_log("파일이 업로드되지 않았습니다.");
    echo json_encode(['success' => false, 'message' => '파일이 업로드되지 않았습니다.']);
}
?> 