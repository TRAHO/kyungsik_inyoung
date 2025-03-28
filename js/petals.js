// 꽃잎 SVG 생성 함수
function createPetalSVG() {
    const colors = ['#fffbce', '#ffc0cb', '#ffdab9', '#ff69b4', '#f4fbf1'];
    const leafColors = ['#98FB98', '#90EE90', '#8FBC8F', '#9ACD32', '#7CCD7C']; // 더 연한 초록색 계열
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '40');
    svg.setAttribute('height', '40');
    svg.setAttribute('viewBox', '0 0 40 40');
    
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    
    // 랜덤하게 진달래 꽃잎, 일반 꽃잎, 나뭇잎 선택
    const type = Math.random();
    if(type < 0.4) {
        // 진달래 꽃잎 모양 (더 뭉툭하고 구부러진 하트 모양)
        const size = 12 + Math.random() * 4; // 12-16 사이로 작게 조정
        const centerX = 20;
        const centerY = 20;
        
        // 진달래 꽃잎의 특성: 더 둥글고 뭉툭한 모양
        const topCurve = size * 0.7;
        const bottomCurve = size * 1.3;
        const sideCurve = size * 1.1;
        
        // 끝부분을 더 둥글게 만들기 위한 추가 제어점
        const topControlY = centerY - topCurve * 0.8;
        const sideControlY = centerY + bottomCurve * 0.2;
        
        // 자연스러운 휘어짐을 위한 추가 제어점
        const bendAmount = Math.random() * 2 - 1;
        const bendX1 = centerX + sideCurve * (1 + bendAmount * 0.2);
        const bendX2 = centerX - sideCurve * (1 + bendAmount * 0.2);
        const bendY = centerY + bottomCurve * 0.3;
        
        path.setAttribute('d', `M${centerX},${centerY - topCurve} 
            C${bendX1},${topControlY} 
            ${bendX1},${bendY} 
            ${centerX},${centerY + bottomCurve} 
            C${bendX2},${bendY} 
            ${bendX2},${topControlY} 
            ${centerX},${centerY - topCurve}`);
            
        path.setAttribute('fill', colors[Math.floor(Math.random() * colors.length)]);
    } else if(type < 0.8) {
        // 기존의 구부러진 꽃잎 모양 (작게)
        const curve1X = 28 + Math.random() * 6;
        const curve1Y = 6 + Math.random() * 3;
        const curve2X = 32 + Math.random() * 3;
        const curve2Y = 12 + Math.random() * 4;
        const endX = 25 + Math.random() * 3;
        const endY = 35;
        
        const roundX1 = endX + Math.random() * 3;
        const roundY1 = endY - 2 - Math.random() * 3;
        const roundX2 = endX - 4 - Math.random() * 4;
        const roundY2 = endY - 1 - Math.random() * 3;
        const roundX3 = 20;
        const roundY3 = endY;
        
        const midX1 = 8 + Math.random() * 4;
        const midY1 = 12 + Math.random() * 6;
        const midX2 = 10 + Math.random() * 4;
        const midY2 = 6 + Math.random() * 4;
        
        path.setAttribute('d', `M20,0 
            C${curve1X},${curve1Y} ${curve2X},${curve2Y} ${endX},${endY} 
            C${roundX1},${roundY1} ${roundX2},${roundY2} ${roundX3},${roundY3} 
            C${midX1},${midY1} ${midX2},${midY2} 20,0`);
            
        path.setAttribute('fill', colors[Math.floor(Math.random() * colors.length)]);
    } else {
        // 나뭇잎 모양
        const width = 1.5 + Math.random() * 1; // 줄기 두께
        const height = 45 + Math.random() * 15; // 잎의 길이
        const bend = Math.random() * 20 - 10; // 줄기 휘어짐 정도
        
        // 나뭇잎의 특성
        const leafWidth = 15 + Math.random() * 5; // 잎의 최대 너비
        
        // 시작점과 끝점
        const startX = 20;
        const startY = 0;
        const endX = 20 + bend;
        const endY = height;
        
        // 잎의 윤곽선
        const leafPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        const leafColor = leafColors[Math.floor(Math.random() * leafColors.length)];
        
        // 벚꽃 잎과 비슷한 모양을 위한 제어점들
        const curve1X = endX + leafWidth * 0.8;
        const curve1Y = height * 0.15;
        const curve2X = endX + leafWidth * 0.9;
        const curve2Y = height * 0.3;
        const endPointX = endX + leafWidth * 0.4;
        const endPointY = height * 0.8;
        
        const roundX1 = endX + leafWidth * 0.3;
        const roundY1 = endY - height * 0.1;
        const roundX2 = endX - leafWidth * 0.3;
        const roundY2 = endY - height * 0.1;
        const roundX3 = endX;
        const roundY3 = endY;
        
        const midX1 = startX - leafWidth * 0.4;
        const midY1 = height * 0.4;
        const midX2 = startX - leafWidth * 0.2;
        const midY2 = height * 0.15;
        
        // 잎의 경로 설정 (벚꽃 잎 모양 + 뾰족한 끝)
        const pathData = `
            M${startX},${startY} 
            C${curve1X},${curve1Y} ${curve2X},${curve2Y} ${endPointX},${endPointY} 
            C${roundX1},${roundY1} ${roundX2},${roundY2} ${roundX3},${roundY3} 
            C${midX1},${midY1} ${midX2},${midY2} ${startX},${startY}`;
        
        leafPath.setAttribute('d', pathData);
        
        // 잎의 스타일 설정
        leafPath.setAttribute('fill', leafColor);
        leafPath.setAttribute('fill-opacity', '0.5');
        leafPath.setAttribute('stroke', leafColor);
        leafPath.setAttribute('stroke-width', width * 0.15);
        
        // SVG에 요소 추가
        svg.appendChild(leafPath);
    }
    
    // 꽃잎에 그라데이션 효과 추가 (나뭇잎 제외)
    if(type < 0.8) {
        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
        gradient.setAttribute('id', `petalGradient_${Math.random().toString(36).substr(2, 9)}`);
        gradient.setAttribute('gradientUnits', 'userSpaceOnUse');
        gradient.setAttribute('x1', '0');
        gradient.setAttribute('y1', '0');
        gradient.setAttribute('x2', '40');
        gradient.setAttribute('y2', '40');
        
        const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop1.setAttribute('offset', '0%');
        stop1.setAttribute('style', `stop-color:${colors[Math.floor(Math.random() * colors.length)]};stop-opacity:1`);
        
        const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
        stop2.setAttribute('offset', '100%');
        stop2.setAttribute('style', `stop-color:${colors[Math.floor(Math.random() * colors.length)]};stop-opacity:0.7`);
        
        gradient.appendChild(stop1);
        gradient.appendChild(stop2);
        defs.appendChild(gradient);
        svg.appendChild(defs);
        
        // 그라데이션 적용
        path.setAttribute('fill', `url(#${gradient.getAttribute('id')})`);
    }
    
    svg.appendChild(path);
    
    // 랜덤하게 좌우 반전 적용 (나뭇잎 제외)
    if(type < 0.8 && Math.random() > 0.5) {
        svg.setAttribute('transform', 'scale(-1, 1) translate(-40, 0)');
    }
    
    return svg;
}

// 꽃잎 생성 및 애니메이션
function createFallingPetal() {
    const container = document.querySelector('.petals-container');
    const petal = document.createElement('div');
    petal.className = 'petal';
    
    const svg = createPetalSVG();
    petal.appendChild(svg);
    container.appendChild(petal);
    
    // 초기 위치 설정
    const startX = Math.random() * window.innerWidth;
    const startY = -50;
    const rotation = Math.random() * 360;
    
    gsap.set(petal, {
        x: startX,
        y: startY,
        rotation: rotation,
        scale: 0.3 + Math.random() * 0.4,
        opacity: 0.6 + Math.random() * 0.4
    });
    
    // 애니메이션 설정
    const duration = 8 + Math.random() * 7; // 더 긴 지속 시간
    const endX = startX + (Math.random() - 0.5) * 300; // 더 넓은 이동 범위
    const endY = window.innerHeight + 50;
    const endRotation = rotation + (Math.random() - 0.5) * 720; // 더 큰 회전
    
    gsap.to(petal, {
        x: endX,
        y: endY,
        rotation: endRotation,
        duration: duration,
        ease: "none",
        onComplete: () => {
            petal.remove();
        }
    });
}

// 주기적으로 꽃잎 생성
function startPetalsAnimation() {
    // 초기 꽃잎 생성
    for(let i = 0; i < 10; i++) { // 꽃잎 갯수 감소
        setTimeout(() => {
            createFallingPetal();
        }, i * 500); // 간격 증가
    }
    
    // 주기적으로 새로운 꽃잎 생성
    setInterval(() => {
        createFallingPetal();
    }, 500); // 간격 증가
}

// 화면 크기 변경 시 대응
window.addEventListener('resize', () => {
    const petals = document.querySelectorAll('.petal');
    petals.forEach(petal => {
        const currentX = gsap.getProperty(petal, 'x');
        const currentY = gsap.getProperty(petal, 'y');
        
        if(currentX > window.innerWidth) {
            gsap.set(petal, 'x', window.innerWidth);
        }
        if(currentY > window.innerHeight) {
            gsap.set(petal, 'y', window.innerHeight);
        }
    });
});

// 애니메이션 시작
startPetalsAnimation(); 