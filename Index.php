<?php
/* PHP 代碼塊 */
require_once("./Utils/AccountInfoDataBaseConnect.php");
require_once("./Utils/CommentsDataBaseConnect.php");

session_start();

// 處理帳戶資訊資料表
$accounntInfoConnect = new AccountInfoDataBaseConnect();
// 如果有 [SESSION_USER] 狀態
if (isset($_SESSION["SESSION_USER"])) {
    var_dump($_SESSION["SESSION_USER"]);
    $accounntInfoConnect->addDefaultAccountInfo($_SESSION["SESSION_USER"]);
}

// 處理評論資訊資料表
$commentsConnect = new CommentsDataBaseConnect();
// 如果有發表評論
if (isset($_POST["comment"]) && isset($_POST["message"])) {
    // 是否已經登入
    if (!isset($_SESSION["SESSION_USER"])) {
        header("Location: login.php");
        return;
    }

    $sendCommentResult = $commentsConnect->addComment($_SESSION["SESSION_USER"], 5, $_POST["message"]);
    header("Location: index.php?comment=$sendCommentResult");
    return;
}
// 處理隨機評論
$commentsResult = $commentsConnect->getRandComments();
?>

<!-- 主網頁 -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁 - 貓之家 | 最好的花店賣家</title>
    <link rel="shortcut icon" href="#">

    <!-- 連結 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./Index.css">
</head>

<body>
    <header>
        <!-- 快捷欄 (移動端或網頁顯示過小) -->
        <label for="toggler" class="fas fa-bars"></label>
        <input type="checkbox" id="toggler" aria-label="toggler">

        <!-- Logo -->
        <a href="#" class="logo">Cat House<span>.</span></a>

        <!-- 網頁導覽 -->
        <nav class="navbar">
            <a href="#home">首頁</a>
            <a href="#about">關於</a>
            <a href="#products">商品</a>
            <a href="#comments">評論</a>
        </nav>

        <!-- 個人資訊 -->
        <div class="icons">
            <a href="#" class="fas fa-shopping-cart"></a>
            <a href="#" class="fas fa-user"></a>
        </div>
    </header>

    <!-- 首頁大綱 -->
    <section class="home" id="home">
        <div class="content">
            <h3>鬱金香</h3>
            <span>天然及美麗的鬱金香</span>
            <p>鬱金香花是一株、一莖、一花，花莖都筆直生長，亭亭玉立，很像荷花，花葉、花莖、花瓣都向上生長，看上去是那麼剛勁有力，意氣風發。鬱金香不像其它花那樣大開大放，而是半開半放。從遠處看，它像花蕾，含苞欲放；走進了看，它確實開放着，還能看到被花粉緊緊包着的花蕊，給人一種含蓄的美。
            </p>
        </div>
    </section>

    <!-- 關於我們 -->
    <section class="about" id="about">
        <!-- 標題 -->
        <h1 class="heading"> <span>關於</span>我們</h1>

        <div class="row">
            <!-- 影片 -->
            <div class="video-container">
                <video src="./Resource/Index-AboutVideo.mp4" loop autoplay muted></video>
                <h3>最 佳 的 花 店 鋪</h3>
            </div>

            <!-- 主內容 -->
            <div class="content">
                <h3>為什麼該選擇我們？</h3>
                <p>我們是您最佳的花供應商，提供最優質芳香的花朵。一切以顧客至上為方針，並且致力於種植最優秀的花朵，讓花朵完美的送到您的手中！</p>
                <p>最後感謝您選擇我們，我們將會做到比現在更好的服務！</p>
            </div>
        </div>
    </section>

    <!-- 特徵 -->
    <section class="icons-container">
        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./Resource/Index-Icon1.png" alt="">
            <div>
                <h3>免運費</h3> <span>在所有訂單上不收取運費</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./Resource/Index-Icon2.png" alt="">
            <div>
                <h3>退款保證</h3> <span>我們保證在出貨七天後退款</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./Resource/Index-Icon3.png" alt="">
            <div>
                <h3>優惠及禮品</h3> <span>註冊我們的會員可享有會員優惠及禮物</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./Resource/Index-Icon4.png" alt="">
            <div>
                <h3>安全支付</h3><span>支持信用卡購買任何您想要的東西</span>
            </div>
        </div>
    </section>

    <!-- 評論區 -->
    <section class="comments-container" id="comments">
        <!-- 標題 -->
        <h1 class="heading">五星<span>評論</span> </h1>

        <!-- 評論顯示框 -->
        <div class="comments-box">
        <?php
        while ($commentRow = $commentsResult->fetch_assoc()) { /* 顯示評論內容並顯示 [WHILE-HEAD] */
            $commentAccountInfoResult = $accounntInfoConnect->getAccountInfo($commentRow["uUUID"]);
            $commentAccountInfoRow = $commentAccountInfoResult->fetch_assoc();
        ?>
            <!-- 評論卡 -->
            <div class="comment">
                <!-- 星星數 -->
                <div class="stars">
                    <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                </div>
                <!-- 內容 -->
                <p> <?php echo($commentRow["cMessage"]); ?> </p>
                <!-- 發表者資訊 -->
                <div class="user">
                    <img src= <?php echo($commentAccountInfoRow["uImageSrc"]); ?> alt="">
                    <div class="user-info">
                        <h3> <?php echo($commentAccountInfoRow["uAlias"]); ?> </h3>
                        <span>發表時間： <?php echo($commentRow["cTime"]); ?> </span>
                    </div>
                </div>
                <span class="fas fa-quote-right"></span>
            </div>
        <?php } /* [WHILE-END] */ ?>
        </div>

        <!-- 發表評論 -->
        <div class="comment-row">
            <div class="comment-box">
                <!-- 接收發送評論結果 -->
                <?php if (isset($_GET["comment"])) {
                    $message = $_GET["comment"];
                    echo ("<div class='comment-messagebox'><h3>$message</h3></div>");
                } ?>

                <!-- 評論須知 -->
                <p>尊重每一個人。 絕對不容忍騷擾、政治迫害、性別歧視、種族主義或仇恨言論。</p>
                <p>Treat everyone with respect. Absolutely no harassment, witch hunting, sexism, racism, or hate speech
                    will be tolerated.</p>

                <!-- 表單 -->
                <form method="POST" action="index.php">
                    <textarea rows="10" name="message" maxlength="200" placeholder="請在這裡輸入您寶貴的想法！"
                        class="field"></textarea>
                    <input type="submit" name="comment" value="發表評論" class="btn">
                </form>
            </div>

            <!-- 圖示 -->
            <div class="image"> <img src="./Resource/Index-SendComment.svg" alt=""> </div>
        </div>
    </section>

    <section class="footer">
        <!-- 聲明 -->
        <div class="credit">Create By <span> Mr. CatHouse </span> | All Rights Reserved</div>
    </section>
</body>