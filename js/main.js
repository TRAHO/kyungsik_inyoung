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


// 참석 여부 폼 표시
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


// 인터뷰 폼 표시
$('.s4 .interview .interviewBtn').on('click', function() {
  $('.interviewPopup').fadeIn();
});
$('.interviewPopup .close').on('click', function() {
  $('.interviewPopup').fadeOut();
});


// 갤러리
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

// 계좌 토글글
$('.s11 .gift .info .item .hd').on('click', function() {
  $(this).toggleClass('active');
  $(this).siblings('.hideBlock').slideToggle();
});


// 방명록 폼 표시
$('.guestbookBtn_write').on('click', function() {
  $('.guestbookPopup').fadeIn();
  $('#guestMessage').siblings('.char-count').find('.current').text('0');
});
$('.guestbookPopup .close').on('click', function() {
  $('.guestbookPopup').fadeOut();
});


// 방명록 수정 버튼 클릭 이벤트
$(document).on('click', '.edit-btn', function() {
    const $item = $(this).closest('.item');
    const id = $item.data('id');
    const name = $item.find('.name').text();
    const message = $item.find('.message').text();
    
    // 비밀번호 입력 팝업 표시
    const password = prompt('비밀번호를 입력하세요:');
    if (!password) return;
    
    // 비밀번호 확인
    $.ajax({
        url: 'check_guestbook_password.php',
        type: 'POST',
        data: {
            id: id,
            password: password
        },
        success: function(response) {
            if (response.success) {
                // 폼에 기존 데이터 채우기
                $('#guestName').val(name);
                $('#guestMessage').val(message);
                
                // 수정 모드로 변경
                $('.guestbookPopup').data('mode', 'edit').data('id', id).data('password', password).fadeIn();
                
                // guestbookListPopup 닫기
                $('.guestbookListPopup').fadeOut();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('서버 오류가 발생했습니다.');
        }
    });
});

// 방명록 폼 제출
$('#guestbookForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        name: $('#guestName').val(),
        message: $('#guestMessage').val()
    };

    if (!formData.name || !formData.message) {
        alert('모든 필드를 입력해주세요.');
        return;
    }

    const mode = $('.guestbookPopup').data('mode');
    const id = $('.guestbookPopup').data('id');
    const password = $('.guestbookPopup').data('password');

    if (mode === 'edit') {
        // 수정 모드일 경우
        formData.id = id;
        formData.password = password;
        $.ajax({
            url: 'update_guestbook.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('.guestbookPopup').fadeOut();
                    loadGuestbookList(); // 방명록 목록 새로고침
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('서버 오류가 발생했습니다.');
            }
        });
    } else {
        // 새로 작성 모드일 경우
        formData.password = $('#guestPassword').val();
        if (!formData.password) {
            alert('비밀번호를 입력해주세요.');
            return;
        }
        
        $.ajax({
            url: 'save_guestbook.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.success) {
                    // 사용자 정보 저장
                    localStorage.setItem('guestbook_user', JSON.stringify({
                        name: formData.name
                    }));
                    
                    // 폼 초기화
                    $('#guestbookForm')[0].reset();
                    
                    alert(response.message);
                    $('.guestbookPopup').fadeOut();
                    loadGuestbookList(); // 방명록 목록 새로고침
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('서버 오류가 발생했습니다.');
            }
        });
    }
});

// 방명록 목록 로드
function loadGuestbookList() {
    $.get('get_guestbook.php', function(data) {
        const $listBox = $('.guestbook .listBox');
        const $listPopup = $('.guestbookListPopup .cont .list');
        
        if (data.length === 0) {
            $listBox.html('<div class="noneItem">아직 작성된 방명록이 없습니다.</div>');
            $listPopup.html('<div class="noneItem">아직 작성된 방명록이 없습니다.</div>');
            return;
        }

        // 최근 5개만 선택
        const recentData = data.slice(0, 5);
        
        // Swiper HTML 생성
        let swiperHtml = `
            <div class="swiper guestbookSwiper">
                <div class="swiper-wrapper">
        `;
        
        recentData.forEach(function(item) {
            swiperHtml += `
                <div class="swiper-slide">
                    <div class="item" data-id="${item.id}">
                        <div class="message">${item.message}</div>
                        <div class="writer">
                            <div class="name">${item.name}</div>
                            <div class="date">${item.created_at}</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        swiperHtml += `
                </div>
            </div>
        `;
        
        $listBox.html(swiperHtml);
        
        // Swiper 초기화
        let s12_swiper = new Swiper('.guestbookSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            speed: 1000,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            loop: true,
        });

        // 전체보기 팝업용 HTML 생성
        let popupHtml = '<div class="list">';
        data.forEach(function(item) {
            const popupItemHtml = `
                <div class="item" data-id="${item.id}">
                    <div class="name">${item.name}</div>
                    <div class="message">${item.message}</div>
                    <div class="date">${item.created_at}</div>
                    <div class="actions">
                        <button type="button" class="edit-btn">수정</button>
                        <button type="button" class="delete-btn">삭제</button>
                    </div>
                </div>
            `;
            popupHtml += popupItemHtml;
        });
        popupHtml += '</div>';
        
        $listPopup.html(popupHtml);
    });
}

// 방명록 삭제 버튼 클릭 이벤트
$(document).on('click', '.delete-btn', function() {
    const $item = $(this).closest('.item');
    const id = $item.data('id');
    
    // 비밀번호 입력 팝업 표시
    const password = prompt('비밀번호를 입력하세요:');
    if (!password) return;
    
    $.ajax({
        url: 'delete_guestbook.php',
        type: 'POST',
        data: {
            id: id,
            password: password
        },
        success: function(response) {
            if (response.success) {
                $item.remove();
                if ($('.guestbook .listBox .list .item').length === 0) {
                    $('.guestbook .listBox').html('<div class="noneItem">아직 작성된 방명록이 없습니다.</div>');
                    localStorage.removeItem('guestbook_user');
                }
                alert(response.message);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('서버 오류가 발생했습니다. 다시 시도해주세요.');
        }
    });
});

// 참석 여부 폼 제출 처리
$('.rsvpWriteBox form').on('submit', function(e) {
    e.preventDefault();
    
    // 폼 데이터 수집
    const formData = {
        guest: $('input[name="guest"]:checked').val(),
        attend: $('input[name="attend"]:checked').val(),
        meal: $('input[name="meal"]:checked').val(),
        name1: $('#name1').val(),
        phone: $('#phone').val(),
        name2: $('#name2').val(),
        message: $('#message').val()
    };

    // 필수 입력값 검증
    if (!formData.guest || !formData.attend || !formData.meal || !formData.name1 || !formData.phone) {
        alert('필수 입력값을 모두 입력해주세요.');
        return;
    }

    // 서버에 데이터 전송
    $.ajax({
        url: 'process_rsvp.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                alert(response.message);
                $('.rsvpWritePopup').fadeOut();
                // 폼 초기화
                $('.rsvpWriteBox form')[0].reset();
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('오류가 발생했습니다. 다시 시도해주세요.');
        }
    });
});


$('.s11 .gift .info .item .hideBlock .bankWrap .copy').on('click', function() {
  navigator.clipboard.writeText($(this).siblings('.bank').find('b').text().replace(/\s+/g, ''));
  alert('계좌번호가 복사되었습니다.');
});


// 페이지 로드 시 방명록 목록 로드
$(document).ready(function() {
    loadGuestbookList();
});

// 전체보기 버튼 클릭 이벤트
$('.guestbookBtn_all').on('click', function() {
    $('.guestbookListPopup').fadeIn();
});

// 전체보기 팝업 닫기
$('.guestbookListPopup .close').on('click', function() {
    $('.guestbookListPopup').fadeOut();
});

// 방명록 글자 수 제한 및 표시
$('#guestMessage').on('input', function() {
    const maxLength = 150;
    const currentLength = $(this).val().length;
    const $charCount = $(this).siblings('.char-count');
    
    $charCount.find('.current').text(currentLength);
    
    if (currentLength > maxLength) {
        $(this).val($(this).val().substring(0, maxLength));
        $charCount.find('.current').text(maxLength);
    }
});

// 함께한 시간 계산 및 표시
function updateTogetherTime() {
    // 시작일 설정 (예: 2025-05-24)
    const startDate = new Date('2025-05-24');
    const now = new Date();
    
    // 시간 차이 계산 (밀리초)
    const diffTime = now - startDate;
    
    // 일, 시간, 분, 초 계산
    const days = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diffTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diffTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diffTime % (1000 * 60)) / 1000);
    
    // 시간 표시 업데이트
    $('.s13 .together .time').text(`${days}일 ${hours}시간 ${minutes}분 ${seconds}초`);
}

// 페이지 로드 시 함께한 시간 표시 및 1초마다 업데이트
$(document).ready(function() {
    updateTogetherTime();
    setInterval(updateTogetherTime, 1000);
    
    // ... existing code ...
});


