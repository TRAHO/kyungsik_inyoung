//smoother
gsap.registerPlugin(ScrollTrigger, ScrollSmoother, ScrollToPlugin, DrawSVGPlugin, SplitText, Observer);
let smoother;
if(	navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) || navigator.maxTouchPoints == 5){
        // 모바일일 경우 scrollsmoother 비활성화
        // ScrollTrigger.normalizeScroll(true);
}else{
    if(window.innerWidth >= 821) {
        smoother = ScrollSmoother.create({
            smooth: 1.4,
            effects: true,
        });
    }
}

// 모바일 브라우저 주소바 자동 숨김 처리 및 전체화면
if(navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i) || navigator.maxTouchPoints == 5){
    // 전체화면 진입 함수
    function enterFullscreen(element) {
        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if(element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }

    // 초기 로드시 전체화면 및 주소바 숨김
    window.addEventListener('load', function() {
        setTimeout(function() {
            enterFullscreen(document.documentElement);
            window.scrollTo(0, 1);
        }, 100);
    });

    // 스크롤시 주소바 숨김
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(function() {
                window.scrollTo(0, 1);
                scrollTimeout = null;
            }, 100);
        }
    });

    // 터치 이벤트시 주소바 숨김
    document.addEventListener('touchstart', function() {
        setTimeout(function() {
            window.scrollTo(0, 1);
        }, 100);
    });

    // 화면 방향 변경시 전체화면 유지
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            enterFullscreen(document.documentElement);
        }, 100);
    });
}

// resize refresh
// let saveWidth = window.innerWidth;
// let resizeTimeout;
// $(window).resize(function() {
//     clearTimeout(resizeTimeout);
//     if(window.innerWidth != saveWidth) {
        
//         resizeTimeout = setTimeout(() => {
//             location.reload();
//             saveWidth = window.innerWidth;
//             ScrollTrigger.refresh();
//         }, 50);

//         // if(window.innerWidth <= 820 && saveWidth > 820) {
//         //     resizeTimeout = setTimeout(() => {
//         //         location.reload();
//         //         saveWidth = window.innerWidth;
//         //         ScrollTrigger.refresh();
//         //     }, 50);
//         // } else if(window.innerWidth > 820 && saveWidth <= 820) {
//         //     resizeTimeout = setTimeout(() => {
//         //         location.reload();
//         //         saveWidth = window.innerWidth;
//         //         ScrollTrigger.refresh();
//         //     }, 50);
//         // } else {
//         //     resizeTimeout = setTimeout(() => {
//         //         ScrollTrigger.refresh();
//         //         saveWidth = window.innerWidth;
//         //     }, 100);
//         // }
//     }
// });