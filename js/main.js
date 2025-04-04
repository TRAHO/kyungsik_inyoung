let scrollUse = false;

window.addEventListener('scroll', (e) => {
  if(!scrollUse) {
    if(e.cancelable) {
      e.preventDefault();
      e.stopPropagation(); 
    }
    return false;
  }
}, {passive: false});

window.addEventListener('touchmove', (e) => {
  if(!scrollUse) {
    if(e.cancelable) {
      e.preventDefault();
      e.stopPropagation();
    }
    return false; 
  }
}, {passive: false});

window.addEventListener('mousewheel', (e) => {
  if(!scrollUse) {
    if(e.cancelable) {
      e.preventDefault();
      e.stopPropagation();
    }
    return false;
  }
}, {passive: false});

window.addEventListener('wheel', (e) => {
  if(!scrollUse) {
    if(e.cancelable) {
      e.preventDefault(); 
      e.stopPropagation();
    }
    return false;
  }
}, {passive: false});



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
            scrollUse = true;
          })
      },

      onStart: () => {
        gsap.to('.intro', {
            background: 'rgba(255, 255, 255, .75)',
            duration: 3
        })
        gsap.to('.intro .txt svg', {
          opacity: 1,
          duration: .2,
          ease: 'sine.inOut'
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


ScrollTrigger.create({
  trigger: '.s3 .profileImg',
  start: 'top 50%',
  end: 'bottom 50%',
  onEnter: () => {
    if(!$('.rsvp').hasClass('active')) {
      scrollUse = false;
      $('.rsvp').fadeIn();
      $('.rsvp').addClass('active');
    }
  }
});
$('.rsvp .close').on('click', function() {
  $('.rsvp').fadeOut();
  scrollUse = true;
});


$('.s4 .interview .interviewBtn').on('click', function() {
  $('.interviewPopup').fadeIn();
  scrollUse = false;
});
$('.interviewPopup .close').on('click', function() {
  $('.interviewPopup').fadeOut();
  scrollUse = true;
});


$('.s11 .gift .info .item .hd').on('click', function() {
  $(this).toggleClass('active');
  $(this).siblings('.hideBlock').slideToggle();
});
