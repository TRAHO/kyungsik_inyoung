-- 방명록 테이블 생성
CREATE TABLE IF NOT EXISTS guestbook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 참석 여부 테이블 생성
CREATE TABLE IF NOT EXISTS rsvp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest ENUM('groom', 'bride') NOT NULL COMMENT '신랑/신부 측',
    attendance ENUM('yes', 'no') NOT NULL COMMENT '참석/불참석',
    meal ENUM('yes', 'no', 'none') NOT NULL COMMENT '식사 여부',
    name1 VARCHAR(50) NOT NULL COMMENT '성함',
    phone VARCHAR(20) NOT NULL COMMENT '연락처',
    name2 VARCHAR(50) COMMENT '동행인 성함',
    message TEXT COMMENT '전달사항',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) NOT NULL COMMENT '수정/삭제용 비밀번호'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 