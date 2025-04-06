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


// 오늘 하루 보지 않기 기능
function setHideForToday() {
  const today = new Date();
  const tomorrow = new Date(today);
  tomorrow.setDate(tomorrow.getDate() + 1);
  tomorrow.setHours(0, 0, 0, 0);
  
  localStorage.setItem('hideRSVPUntil', tomorrow.getTime());
}

function shouldShowRSVP() {
  const hideUntil = localStorage.getItem('hideRSVPUntil');
  if (!hideUntil) return true;
  
  const now = new Date().getTime();
  return now >= parseInt(hideUntil);
}

// RSVP 팝업 표시 로직 수정
ScrollTrigger.create({
  trigger: '.s3 .profileImg',
  start: 'top 50%',
  end: 'bottom 50%',
  onEnter: () => {
    if(!$('.rsvp').hasClass('active') && shouldShowRSVP()) {
      scrollUse = false;
      $('.rsvp').fadeIn();
      $('.rsvp').addClass('active');
    }
  }
});

// 오늘 하루 보지 않기 체크박스 이벤트
$('.rsvp .check input').on('change', function() {
  if($(this).is(':checked')) {
    setHideForToday();
  } else {
    localStorage.removeItem('hideRSVPUntil');
  }
});
$('.rsvp .hideToday').on('click', function() {
  setHideForToday();
  $('.rsvp').fadeOut();
  scrollUse = true;
});
$('.rsvp .close').on('click', function() {
  $('.rsvp').fadeOut();
  scrollUse = true;
});


$('.rsvp .rsvpBtn').on('click', function() {
  $('.rsvp').fadeOut();
  $('.rsvpWritePopup').fadeIn();
  scrollUse = true;
});
$('.s10 .visit .visitBtn').on('click', function() {
  $('.rsvpWritePopup').fadeIn();
});
$('.rsvpWritePopup .close').on('click', function() {
  $('.rsvpWritePopup').fadeOut();
});


$('.s4 .interview .interviewBtn').on('click', function() {
  $('.interviewPopup').fadeIn();
});
$('.interviewPopup .close').on('click', function() {
  $('.interviewPopup').fadeOut();
});


$(document).on('load',function() {
  $("#lightgallery").lightGallery({
      // 옵션 설정
      mode: 'lg-slide',
      speed: 600,
      download: false,
      counter: true,

      controls: true,    // 컨트롤 버튼 표시
      closable: true,    // 닫기 버튼 표시
      escKey: true,      // ESC 키로 닫기 가능
      keyPress: true,    // 키보드 컨트롤 활성화
      zoomFromOrigin: false,
      actualSize : false,
      showZoom: false,
      zoom : false,
      fullScreen : false,
      share : false,
      autoplayControls : false,

      thumbnail: false
  });
});


$('.s11 .gift .info .item .hd').on('click', function() {
  $(this).toggleClass('active');
  $(this).siblings('.hideBlock').slideToggle();
});
