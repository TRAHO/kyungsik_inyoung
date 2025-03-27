<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head1.php"; // 공통 head
?>
<!--메타태그 삽입 위치-->
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head2.php"; // 공통 스크립트 관리
    include_once $_SERVER["DOCUMENT_ROOT"]."/comp/header.php"; // 공통 스크립트 관리
?>

<link rel="stylesheet" href="/css/main.css">
<style>
    h1 + h1 {margin-top: 100px;}
    
</style>
<!-- 

인트로 : 이펙트 꽃잎, 드로잉 텍스트
대문 : 배경 이미지, 드로잉 텍스트, 꽃잎, 신랑신부 이름

-->

<div id="smooth-wrapper">
    <div id="smooth-content">
        <main class="main">
            <section class="s1">
                <div class="intro">
                    <div class="txt">
                        <h1 class="introTxt">We're getting <br>married! </h1>
                    </div>
                </div>

                <div class="contents">
                    <div class="txt">
                        <!-- <p>경식 & 인영</p> -->
                        <h1 class="introTxt0">1. We're getting <br>married!</h1>
                        <h1 class="introTxt1">2. We're getting <br>married!</h1>
                        <h1 class="introTxt2">3. We're getting <br>married!</h1>
                        <h1 class="introTxt3"><span style="font-family: 'Breathing';">4.</span> We're getting <br>married!</h1>
                        <h1 class="introTxt4">5. We're getting <br>married!</h1>
                        <h1 class="introTxt5">6. We're getting <br>married!</h1>
                        <h1 class="introTxt6">7. We're getting <br>married!</h1>
                        <h1 class="introTxt7">8. We're getting <br>married!</h1>
                        <h1 class="introTxt8">9. We're getting <br>married!</h1>
                        <h1 class="introTxt9">10. We're getting <br>married!</h1>
                        <h1 class="introTxt10">11. We're getting <br>married!</h1>
                    </div>
                </div>
            </section>
        </main>

        <?php
            include_once $_SERVER["DOCUMENT_ROOT"]."/comp/footer.php";
        ?>
    </div>
</div>


<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_end.php";
?>