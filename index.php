<?php
/**
 * Copyright (C) 2022 MouBieCat
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/* PHP 代碼塊 */
require_once("./utils/AccountDataBaseConnect.php");
require_once("./utils/AccountInfoDataBaseConnect.php");
require_once("./utils/OrdersDataBaseConnect.php");
require_once("./utils/ProductsDataBaseConnect.php");
require_once("./utils/CommentDataBaseConnect.php");

session_start();

$__NOW_PAGE = 1;
$__SEARCH = NULL;

$__ACCOUNT_DB = new AccountDataBaseConnect();

$__ACCOUNT_INFO_DB = new AccountInfoDataBaseConnect();
$__ACCOUNT_INFO_RESULT = NULL;

$__ORDERS_DB = new OrdersDataBaseConnect();
$__ORDERS_RESULT = NULL;

$__PRODUCTS_DB = new ProductsDataBaseConnect();
$__PRODUCTS_MAX_PAGE = 0;
$__PRODUCTS_ALL_RESULT = NULL;
$__PRODUCTS_OF_PAGE_RESULT = NULL;

$__COMMENTS_DB = new CommentDataBaseConnect();
$__COMMENTS_RAND_RESULT = NULL;

/* -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/ */

// 處理帳戶資訊資料表
if (isset($_SESSION["SESSION_USER"])) {

    // 修改密碼操作
    if (isset($_POST["ChangePasswordButton"])) {
        $resultArray = $__ACCOUNT_DB->setNewPassword($_SESSION["SESSION_USER"], $_POST["ChangeOldPasswordTextBox"], $_POST["ChangeNewPasswordTextBox"]);

        if ($resultArray[__RETURN_RESULT__] === TRUE) {
            session_destroy();
            header("Location: index.php");
            return;
        }
    }

    // 修改帳戶資訊操作
    if (isset($_POST["ChangeAccountInfoButton"])) {
        $__ACCOUNT_INFO_DB->setAccountInfoAlias($_SESSION["SESSION_USER"], $_POST["ChangeAccountInfoAliasTextBox"]);

        $__ACCOUNT_INFO_DB->setAccountInfoPhone($_SESSION["SESSION_USER"], $_POST["ChangeAccountInfoPhoneTextBox"]);

        $__ACCOUNT_INFO_DB->setAccountInfoEmail($_SESSION["SESSION_USER"], $_POST["ChangeAccountInfoEmailTextBox"]);

        if ($_FILES['ChangeAccountInfoImageTextBox']['type'] == 'image/png' || $_FILES['ChangeAccountInfoImageTextBox']['type'] == 'image/jpeg') {
            $movFilePathName = "./accounts/" . $_SESSION["SESSION_USER"];
            //移動暫存到實體路徑
            if (move_uploaded_file($_FILES['ChangeAccountInfoImageTextBox']['tmp_name'], $movFilePathName))
                $__ACCOUNT_INFO_DB->setAccountInfoImage($_SESSION["SESSION_USER"], $movFilePathName);
        }
    }

    /* 是否有登出請求 */
    if (isset($_POST["LoginOutButton"])) {
        session_destroy();
        header("Location: index.php");
        return;
    }

    $__ACCOUNT_INFO_RESULT = $__ACCOUNT_INFO_DB->getAccountInfo($_SESSION["SESSION_USER"]);
}

/* -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/ */

// 處理訂單資料表
if (isset($_POST["OrderButton"])) {
    if (!isset($_SESSION["SESSION_USER"])) {
        header("Location: login.php");
        return;
    }

    if (isset($_POST["AddProductTextBox"]))
        $__ORDERS_DB->addOrder($_SESSION["SESSION_USER"], $_POST["AddProductTextBox"], 1);

    if (isset($_POST["RemoveProductTextBox"]))
        $__ORDERS_DB->removeOrder($_SESSION["SESSION_USER"], $_POST["RemoveProductTextBox"]);
}

if (isset($_SESSION["SESSION_USER"])) {
    $__ORDERS_DB->clearInvalidOrders($_SESSION["SESSION_USER"]);
    $__ORDERS_RESULT = $__ORDERS_DB->getOrdersByUUID($_SESSION["SESSION_USER"]);
}

/* -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/ */

// 處理商品資訊資料表
if (isset($_GET["search"]))
    $__SEARCH = $_GET["search"];

$__PRODUCTS_ALL_RESULT = $__PRODUCTS_DB->getProducts($__SEARCH); // 所有商品
$__PRODUCTS_MAX_PAGE = ceil($__PRODUCTS_ALL_RESULT->num_rows / 5);

if (isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $__PRODUCTS_MAX_PAGE)
    $__NOW_PAGE = $_GET["page"];

$__PRODUCTS_OF_PAGE_RESULT = $__PRODUCTS_DB->getProductsOfPage($__NOW_PAGE, 5, $__SEARCH); // 該頁數所顯示的商品

/* -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/ */

// 處理評論資訊資料表
if (isset($_POST["CommentButton"])) {
    if (!isset($_SESSION["SESSION_USER"])) {
        header("Location: login.php");
        return;
    }

    $sendCommentResult = $__COMMENTS_DB->addComment($_SESSION["SESSION_USER"], 5, $_POST["CommentTextarea"]);
    header("Location: index.php?comment=" . $sendCommentResult[__RETURN_CONTENT__]);
}

$__COMMENTS_RAND_RESULT = $__COMMENTS_DB->getRnadComments(); // 隨機評論

/* -/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/-/ */
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
    <link rel="stylesheet" type="text/css" href="./index.css">
</head>

<body>
    <!-- 頭部 -->
    <header>
        <!-- 快捷欄 ( 移動端顯示 ) -->
        <label for="toggler" class="fas fa-bars"></label>
        <input type="checkbox" id="toggler" aria-label="toggler">

        <!-- 商標 -->
        <a href="index.php" class="logo">Cat House<span>.</span></a>

        <!-- 網頁導覽 -->
        <nav class="navbar">
            <a href="#home">首頁</a>
            <a href="#about">關於</a>
            <a href="#products">商品</a>
            <a href="#comments">評論</a>
        </nav>

        <!-- 資訊表 -->
        <div class="icons">
            <!-- 查找 -->
            <label id="switch-search" class="fas fa-search"></label>
            <!-- 商品搜索框 -->
            <div class="search" id="search">
                <form method="GET" action="index.php">
                    <input type="text" name="search" placeholder="請輸入查找的相關商品關鍵字">
                    <button type="submit" class="fas fa-search" aria-label="search-product"></button>
                </form>
            </div>

            <!-- 購物清單 -->
            <label id="switch-shopping" class="fas fa-shopping-cart"></label>
            <!-- 購買清單資訊卡 -->
            <div class="shopping" id="shopping">
                <?php
                $totalMoney = 0;
                if ($__ORDERS_RESULT !== NULL) {
                    while ($orderRow = $__ORDERS_RESULT->fetch_assoc()) {
                        $productRow = $__PRODUCTS_DB->getProduct($orderRow["oProduct"])->fetch_assoc();
                        $productTotalMoney = $productRow["pPrice"] * $orderRow["oCount"];
                        $totalMoney += $productTotalMoney;
                ?>
                <!-- 購買項目 -->
                <div class="box">
                    <!-- 刪除訂單 -->
                    <form method="POST" action="index.php">
                        <input type="text" style="display: none;" name="RemoveProductTextBox"
                            title="RemoveProductTextBox" value=<?php echo ($productRow["pID"]); ?>>
                        <button type="submit" class="fas fa-trash" name="OrderButton"
                            aria-label="remove-order"></button>
                    </form>

                    <!-- 商品圖片來源 -->
                    <img src=<?php echo ($productRow["pImageSrc"]); ?> alt="">
                    <div class="content">
                        <!-- 商品標題 -->
                        <h3 class="title">
                            <?php echo ($productRow["pTitle"]); ?>
                        </h3>
                        <!-- 價格 * 數量 = 小計 -->
                        <span class="price-count">
                            小計：$
                            <?php echo ($productTotalMoney); ?> -
                            數量：
                            <?php echo ($orderRow["oCount"]); ?>
                        </span>
                        <!-- 貨物是否不足 -->
                        <span class="lack-of-demand">
                            <?php if ($orderRow["oCount"] > $productRow["pCount"])
                            echo ("<br />目前貨物正在短缺中"); ?>
                        </span>
                    </div>
                </div>
                <?php } /* [WHILE-END] */
                } /* [IF-END] */?>

                <!-- 結帳及總金額 -->
                <p>合計：$
                    <?php echo ($totalMoney); ?>
                </p>
                <a href="#">前往結帳</a>
            </div>

            <!-- 使用者 -->
            <label id="switch-user" class="fas fa-user"></label>
            <!-- 使用者資訊 -->
            <div class="user" id="user">
                <?php if ($__ACCOUNT_INFO_RESULT !== NULL) { /* [IF-HEAD] */
                    $accountRow = $__ACCOUNT_DB->getAccountOfUUID($_SESSION["SESSION_USER"])->fetch_assoc();
                    $accountInfoRow = $__ACCOUNT_INFO_RESULT->fetch_assoc();
                ?>
                <!-- 使用者資訊 -->
                <div class="user-info">
                    <img src=<?php echo ($accountInfoRow["iImageSrc"]); ?> alt="">
                    <div>
                        <h3>
                            <?php echo ($accountInfoRow["iAlias"]); ?>
                        </h3>
                        <span>
                            <?php echo ($accountRow["uName"]); ?>
                        </span>
                    </div>
                </div>

                <p>安全性配置</p>
                <!-- 變更使用者資訊 -->
                <form method="POST" action="index.php">
                    <input type="password" name="ChangeOldPasswordTextBox" title="ChangeOldPasswordTextBox"
                        placeholder="請輸入舊的密碼">
                    <input type="password" name="ChangeNewPasswordTextBox" title="ChangeNewPasswordTextBox"
                        placeholder="請輸入新的密碼">

                    <button type="submit" name="ChangePasswordButton" aria-label="ChangePasswordButton">
                        變更密碼</button>
                </form>

                <p>帳戶基礎配置</p>
                <!-- 變更使用者資訊 -->
                <form method="POST" action="index.php" enctype="multipart/form-data">
                    <input type="text" name="ChangeAccountInfoAliasTextBox" title="ChangeAccountInfoAliasTextBox"
                        placeholder="Member" value=<?php echo ($accountInfoRow["iAlias"]); ?>>

                    <input type="tel" name="ChangeAccountInfoPhoneTextBox" title="ChangeAccountInfoPhoneTextBox"
                        placeholder="0-123-456-789" value=<?php echo ($accountInfoRow["iPhone"]); ?>>

                    <input type="email" name="ChangeAccountInfoEmailTextBox" title="ChangeAccountInfoEmailTextBox"
                        placeholder="example@example.com" value=<?php echo ($accountInfoRow["iEmail"]); ?>>

                    <input type="file" name="ChangeAccountInfoImageTextBox" title="ChangeAccountInfoImageTextBox">

                    <button type="submit" name="ChangeAccountInfoButton" aria-label="ChangeAccountInfoButton">
                        修改資訊</button>
                </form>

                <p>其他操作</p>
                <!-- 登出 -->
                <form method="POST" action="index.php">
                    <button type="submit" name="LoginOutButton" aria-label="LoginOutButton">
                        登出</button>
                </form>
                <?php } /* [IF-END] */else
                    echo ("<h3>請先進行<a href='login.php'>登錄</a><h3>"); ?>
            </div>
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
                <video src="./resource/about-video.mp4" loop autoplay muted></video>
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
            <img src="./resource/Icons-1.png" alt="">
            <div>
                <h3>免運費</h3> <span>在所有訂單上不收取運費</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./resource/icons-2.png" alt="">
            <div>
                <h3>退款保證</h3> <span>我們保證在出貨七天後退款</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./resource/icons-3.png" alt="">
            <div>
                <h3>優惠及禮品</h3> <span>註冊我們的會員可享有會員優惠及禮物</span>
            </div>
        </div>

        <!-- 特徵卡 -->
        <div class="icons">
            <img src="./resource/Icons-4.png" alt="">
            <div>
                <h3>安全支付</h3><span>支持信用卡購買任何您想要的東西</span>
            </div>
        </div>
    </section>

    <!-- 商品展示 -->
    <section class="products" id="products">
        <!-- 標題 -->
        <h1 class="heading">所有<span>商品</span> </h1>

        <!-- 商品內容框 -->
        <div class="box-container">
            <?php while ($productRow = $__PRODUCTS_OF_PAGE_RESULT->fetch_assoc()) { /* [WHILE-HEAD] */?>
            <!-- 商品資訊 -->
            <div class="box">
                <!-- 是否為限定商品 -->
                <?php if ($productRow["pEvent"] == TRUE)
                    echo ("<span class='event'>限定</span>"); ?>

                <!-- 圖片 -->
                <div class="image">
                    <!-- 圖片來源 -->
                    <img src=<?php echo ($productRow["pImageSrc"]); ?> alt="">

                    <!-- 新增訂單 -->
                    <form method="POST" action="index.php">
                        <input type="text" style="display: none;" name="AddProductTextBox" title="AddProductTextBox"
                            value=<?php echo ($productRow["pID"]); ?>>
                        <button type="submit" class="fas fa-shopping-cart" name="OrderButton" aria-label="add-order">
                            添加至購物車</button>
                    </form>
                </div>
                <!-- 商品資訊 -->
                <div class="content">
                    <!-- 商品標題 -->
                    <h3>
                        <?php echo ($productRow["pTitle"]); ?>
                    </h3>
                    <!-- 價格、庫存 -->
                    <div class="price">$
                        <?php echo ($productRow["pPrice"]); ?> <span>庫存：
                            <?php echo ($productRow["pCount"]); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php } /* [WHILE-END] */?>
        </div>

        <!-- 分頁按鈕 -->
        <div class="page-container">
            <?php
            /* 是否有更多頁數 ( _NOW - 2 > 1 ) */
            if ($__NOW_PAGE - 2 > 1)
                echo ("<a href='index.php?page=1&search=$__SEARCH' class='more'>1...</a>");
            for ($tempPage = $__NOW_PAGE - 2; $tempPage <= $__NOW_PAGE + 2; $tempPage++) {
                /* 如果頁數不合法 */
                if ($tempPage < 1 || $tempPage > $__PRODUCTS_MAX_PAGE)
                    continue;
                /* 該按鈕將被如何顯示 */
                if ($tempPage == $__NOW_PAGE)
                    echo ("<a class='select'>$tempPage</a>");
                else
                    echo ("<a href='index.php?page=$tempPage&search=$__SEARCH' class='no-select'>$tempPage</a>");
            }
            /* 是否有更多頁數 ( _NOW + 2 < _MAX ) */
            if ($__NOW_PAGE + 2 < $__PRODUCTS_MAX_PAGE)
                echo ("<a href='index.php?page=$__PRODUCTS_MAX_PAGE&search=$__SEARCH' class='more'>...$__PRODUCTS_MAX_PAGE</a>");
            ?>
        </div>
    </section>

    <!-- 帳戶評論 -->
    <section class="comments" id="comments">
        <!-- 標題 -->
        <h1 class="heading">五星<span>評論</span> </h1>

        <!-- 評論內容框 -->
        <div class="box-container">
            <?php
            while ($randCommentRow = $__COMMENTS_RAND_RESULT->fetch_assoc()) {
                $commentAccountInfoResult = $__ACCOUNT_INFO_DB->getAccountInfo($randCommentRow["cUUID"]);
                $commentAccountInfoRow = $commentAccountInfoResult->fetch_assoc(); /* [WHILE-HEAD] */?>
            <!-- 帳戶評論 -->
            <div class="box">
                <!-- 星星 -->
                <div class="stars">
                    <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i
                        class="fas fa-star"></i> <i class="fas fa-star"></i>
                </div>
                <!-- 發表內容 -->
                <p>
                    <?php echo ($randCommentRow["cMessage"]); ?>
                </p>
                <!-- 發表者資訊 -->
                <div class="user">
                    <img src=<?php echo ($commentAccountInfoRow["iImageSrc"]); ?> alt="">
                    <div>
                        <h3>
                            <?php echo ($commentAccountInfoRow["iAlias"]); ?>
                        </h3>
                        <span>發表時間：
                            <?php echo ($randCommentRow["cTime"]); ?>
                        </span>
                    </div>
                </div>
                <!-- 逗號樣式 -->
                <span class="fas fa-quote-right"></span>
            </div>
            <?php } /* [WHILE-END] */?>
        </div>

        <!-- 發表評論區 -->
        <div class="row">
            <!-- 發表評論區塊 -->
            <div class="comment">
                <!-- 接收發送評論結果 -->
                <?php if (isset($_GET["comment"])) {
                    $message = $_GET["comment"];
                    echo ("<div class='messagebox'><h3>$message</h3></div>");
                }
                ?>

                <!-- 評論須知 -->
                <p>尊重每一個人。 絕對不容忍騷擾、政治迫害、性別歧視、種族主義或仇恨言論。</p>
                <p>Treat everyone with respect. Absolutely no harassment, witch hunting, sexism, racism, or hate speech
                    will be tolerated.</p>

                <!-- 評論表單 -->
                <form method="POST" action="index.php">
                    <!-- 內容輸入框 -->
                    <textarea rows="10" name="CommentTextarea" maxlength="256" placeholder="請在這裡輸入您寶貴的想法！" class="field"
                        required></textarea>
                    <!-- 送出 -->
                    <input type="submit" name="CommentButton" value="發表評論">
                </form>
            </div>

            <!-- 圖示 -->
            <div class="image"> <img src="./resource/images-comments.svg" alt=""> </div>
        </div>
    </section>

    <!-- 網頁尾部 -->
    <footer>
        <!-- 聲明 -->
        <div class="credit">Create By <span> Mr. CatHouse </span> | All Rights Reserved</div>
    </footer>

    <!-- JavaScript -->
    <script src="index.js"></script>
</body>
</body>