<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head1.php"; // 공통 head
?>
<!--메타태그 삽입 위치-->
<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_head2.php"; // 공통 스크립트 관리
    include_once $_SERVER["DOCUMENT_ROOT"]."/comp/header.php"; // 공통 스크립트 관리
?>

<link rel="stylesheet" href="/css/main.css">


<div id="smooth-wrapper">
    <div id="smooth-content">
        <main class="main">
            <div class="preloader">
                <h1>신경식 ♥ 김인영</h1>

                <style>
                    body {
                        background-color:rgb(187, 224, 183);
                    }
                    .preloader {
                        background-image: url(/asset/img/pre_bg.jpg);
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                        position: relative;
                        width: 100%;
                        height: 100vh;
                        max-width: 768px;
                        margin: 0 auto;
                    }
                    .preloader::before {
                        content: "";
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5);   
                    }
                    .preloader h1 {
                        z-index: 1;
                        font-size: 30px;
                        font-weight: bold;
                        color: #fff;
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                    }

                </style>
            </div>
        </main>

        <?php
            include_once $_SERVER["DOCUMENT_ROOT"]."/comp/footer.php";
        ?>
    </div>
</div>


<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/layout/_end.php";
?>