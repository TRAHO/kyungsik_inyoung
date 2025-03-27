<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head1.php"; // 공통 head
?>
<!--메타태그 삽입 위치-->
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head2.php"; // 공통 스크립트 관리
    include_once $_SERVER["DOCUMENT_ROOT"]."/comp/header.php"; // 공통 스크립트 관리
?>

<link rel="stylesheet" href="/css/main.css">

<!-- 

인트로 : 이펙트 꽃잎, 드로잉 텍스트 (흰색 배경 시작 동시에 꽃잎 이펙트 시작 > 드로잉 텍스트 시작 동시에 배경 점점 투명하게 > 배경이 완전히 투명해지기 전 텍스트 위치 이동 후 배경 투명화 종류 후 인트로 숨김)
대문 : 배경 이미지, 드로잉 텍스트, 꽃잎, 신랑신부 이름

인사텍스트 : 이 모든 것 위에 사랑을 더하라 이는 온전하게 매는 띠니라 -골로새서 3장 14절-
초대 및 감사 : 동일

프로필
신랑 신경식 95년 10월 11일 INFP 김옥녀의 손자
신부 김인영 99년 12월 24일 ISTJ 김종덕, 이미향의 장녀

인터뷰


-->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main class="main">
            <section class="s1">
                <div class="intro">
                    <div class="txt">
                        <h1 class="introTxt">We're getting <br>Married!</h1>
                    </div>
                </div>

                <div class="contents">
                    <div class="txt">
                        <!-- <p>경식 & 인영</p> -->
                        <h1 class="introTxt">We're getting <br>Married!</h1>
                    </div>
                </div>
            </section>
        </main>

        <?php
            include_once $_SERVER["DOCUMENT_ROOT"]."/comp/footer.php";
        ?>
    </div>
</div>

<script src="/js/main.js"></script>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_end.php";
?>