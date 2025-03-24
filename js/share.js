//smoother
gsap.registerPlugin(ScrollTrigger, ScrollSmoother, ScrollToPlugin, DrawSVGPlugin, MorphSVGPlugin, SplitText, Observer);
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