// gsap.from('.s1 .svgDraw path', {duration: 1, drawSVG: 0, ease: 'none'});
let paths = document.querySelectorAll('#pathLayer > g > *');
let duration = 7,
    distance = 0,
    tl = gsap.timeline({
        onComplete: () => {
            
        }
    });
paths.forEach(segment => distance += segment.getTotalLength());
paths.forEach(segment => {
  tl.from(segment, {
    drawSVG: 0,
    duration: duration * (segment.getTotalLength() / distance),
    ease: "power2.inOut"
  });
});