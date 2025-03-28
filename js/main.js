// gsap.from('.s1 .svgDraw path', {duration: 1, drawSVG: 0, ease: 'none'});
let paths = document.querySelectorAll('#pathLayer > g > *');
let duration = 4,
    distance = 0,
    tl = gsap.timeline({
        onComplete: () => {
            gsap.timeline({})
            .to('.intro .svgDraw', {
              top: '85%', scale: 0.75,
              duration: 1, ease: 'sine.inOut', delay: .5
            })
            .to('.intro', {
                background: 'rgba(255, 255, 255, 0)',
                'backdrop-filter': 'blur(0px)',
                duration: 1,
            } ,'-=100%')
            .to('.s1 .intro .txt svg *', {
              stroke: '#ff85a2',
              duration: 1, ease: 'sine.inOut'
            }, '-=100%')
            .to('.contents .txt', {
                opacity: 1,
                duration: .6,
            })
            .to('.intro', {
              opacity: 0,
              duration: .4,
            })
        },
        onStart: () => {
            gsap.to('.intro', {
                background: 'rgba(255, 255, 255, .8)',
                duration: 5,
            })
        }
    });
paths.forEach(segment => distance += segment.getTotalLength());
paths.forEach(segment => {
  tl.from(segment, {
    drawSVG: 0,
    duration: duration * (segment.getTotalLength() / distance),
    ease: "power3.inOut"
  });
});