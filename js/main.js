// gsap.from('.s1 .svgDraw path', {duration: 1, drawSVG: 0, ease: 'none'});
window.addEventListener('load', () => {
  setTimeout(() => {
    window.scrollTo(0, 1);
  }, 500);
});
let paths = document.querySelectorAll('#pathLayer > g > *');
let duration = 3,
    distance = 0,
    tl = gsap.timeline({
      delay: .4,
      onComplete: () => {
          gsap.timeline({})
          .to('.intro .svgDraw', {
            top: '85%', scale: 0.75,
            duration: 1.2, ease: 'sine.inOut', delay: .5
          })
          .to('.s1 .intro .txt svg *', {
            stroke: '#ff85a2',
            duration: 1, ease: 'sine.inOut'
          }, '-=100%')
          .to('.intro', {
              background: 'rgba(255, 255, 255, 0)',
              'backdrop-filter': 'blur(0px)',
              duration: 1,
          } ,'-=100%')
          .to('.contents .txt', {
            opacity: 1,
            duration: .4,
          })
          .to('.intro .txt', {
            opacity: 0,
            duration: .4,
            delay: .2
          })
          .add(() => {
            $('body').css('overflow', 'auto');
          })
      },

      onStart: () => {
        gsap.to('.intro', {
            background: 'rgba(255, 255, 255, .75)',
            duration: 3
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