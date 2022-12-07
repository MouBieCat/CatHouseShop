<!-- 主網頁 -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁 - 貓之家 | 最好的花店賣家</title>

    <!-- 連結 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./Index.css">
    <?php
    /* <!-- 代碼 --> */
    include "DataBaseConnection.php";

    final class ProductsDataBaseConnect extends DataBaseConnect {
        /**
         * 資料庫 Products 表資訊
         * 
         * CREATE TABLE Products(
         *   pUUID varchar(36) PRIMARY KEY NOT NULL, 
         *   pTitle varchar(32) NOT NULL, 
         *   pImageSrc varchar(256) NOT NULL, 
         *   pPrice INT NOT NULL, 
         *   pDiscountProduct INT NOT NULL DEFAULT 0
         * );
         * 
         * DESCRIBE Products;
         * +------------------+--------------+------+-----+---------+-------+
         * | Field            | Type         | Null | Key | Default | Extra |
         * +------------------+--------------+------+-----+---------+-------+
         * | pUUID            | varchar(36)  | NO   | PRI | NULL    |       |
         * | pTitle           | varchar(32)  | NO   |     | NULL    |       |
         * | pImageSrc        | varchar(256) | NO   |     | NULL    |       |
         * | pPrice           | int          | NO   |     | NULL    |       |
         * | pDiscountProduct | int          | NO   |     | 0       |       |
         * +------------------+--------------+------+-----+---------+-------+
         */

        /**
        * 建構子
        */
        public function __construct() {
            parent::__construct();
        }

        /**
         * 獲取指定頁數的商品
         * 
         * @param _Page  頁數
         * @param _Count 數量
         * @return 查詢結果
         */
        public function getProductForPage(int $_Page = 1, int $_Count = 5) {
            $startIndex = ($_Page - 1) * $_Count;
            $selectCommand = "SELECT * FROM Products LIMIT $startIndex, $_Count";
            return $this->m_ConnectObject->query($selectCommand);
        }

        /**
         * 獲取所有商品
         * 
         * @return 查詢結果
         */
        public function getProducts() {
            $selectCommand = "SELECT * FROM Products";
            return $this->m_ConnectObject->query($selectCommand);
        }
    }

    final class CommentDataBaseConnect extends DataBaseConnect {
        /**
        * 資料庫 Comments 表資訊
        * 
        * CREATE TABLE Comments(
        *   cName varchar(20) NOT NULL, 
        *   cStars tinyint NOT NULL, 
        *   cMessage varchar(150) NOT NULL, 
        *   cTime varchar(20) NOT NULL
        * );
        * 
        * DESCRIBE Comments;
        * +----------+--------------+------+-----+---------+-------+
        * | Field    | Type         | Null | Key | Default | Extra |
        * +----------+--------------+------+-----+---------+-------+
        * | cName    | varchar(20)  | NO   |     | NULL    |       |
        * | cStars   | tinyint      | NO   |     | NULL    |       |
        * | cMessage | varchar(150) | NO   |     | NULL    |       |
        * | cTime    | varchar(20)  | NO   |     | NULL    |       |
        * +----------+--------------+------+-----+---------+-------+
        */
        /**
        * 建構子
        */
        public function __construct() {
            parent::__construct();
        }

        /**
        * 從資料庫隨機撈取數比資料
        * 
        * @param $_Count 撈取數量
        * @return 撈取結果
        */
        public function getComments(int $_Count = 3, int $_Stars = 5) {
            $selectCommand = "SELECT * FROM Comments WHERE cStars=$_Stars order by rand() LIMIT $_Count";
            return $this->m_ConnectObject->query($selectCommand);
        }
    }

    /* 商品資料庫連結 */
    $productConnection = new ProductsDataBaseConnect();
    $productResultAll  = $productConnection->getProducts();
    $productTotalPage  = ceil($productResultAll->num_rows / 5);

    /* 頁數處理 */
    $GlobalValues_Page = 1;
    if (isset($_GET["page"]) && $_GET["page"] <= $productTotalPage && $_GET["page"] > 0) $GlobalValues_Page = $_GET["page"]; // 異常頁數處理判斷：如果指定頁數大於最大頁數
    $productResultForPage = $productConnection->getProductForPage($GlobalValues_Page);

    /* 評論資料庫連結 */
    $commentConnection = new CommentDataBaseConnect();
    $commentResult     = $commentConnection->getComments();
    ?>
</head>
<body>
    <header>
        <!-- 快捷欄 (移動端或網頁顯示過小) -->
        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>

        <!-- Logo -->
        <a href="#" class="logo">Cat House<span>.</span></a>

        <!-- 網頁導覽 -->
        <nav class="navbar">
            <a href="#home">首頁</a>
            <a href="#about">關於</a>
            <a href="#products">商品</a>
            <a href="#review">評論</a>
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
            <p>鬱金香花是一株、一莖、一花，花莖都筆直生長，亭亭玉立，很像荷花，花葉、花莖、花瓣都向上生長，看上去是那麼剛勁有力，意氣風發。鬱金香不像其它花那樣大開大放，而是半開半放。從遠處看，它像花蕾，含苞欲放；走進了看，它確實開放着，還能看到被花粉緊緊包着的花蕊，給人一種含蓄的美。</p>
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
                <h3>安全支付</h3><span>可使用信用卡支付訂單</span>
            </div>
        </div>
    </section>

    <!-- 商品展示 -->
    <section class="products" id="products">
        <!-- 標題 -->
        <h1 class="heading">所有<span>商品</span> </h1>

        <!-- 商品內容框 -->
        <div class="box-container">
        <?php if ($productResultForPage->num_rows === 0) echo("<div class='nothing-box'><h3>暫時沒有更多的商品<h3></div>"); else { /* 如果沒有任何商品 [header] */ ?>
        <?php while ($productRow = $productResultForPage->fetch_assoc()) { /* 處理並顯示當前頁數的商品 [header] */ ?>
            <!-- 商品 -->
            <div class="box">
                <!-- 商品圖片卡 -->
                <div class="image">
                    <img src= "Products/<?php echo($productRow["pImageSrc"]); ?>" alt="">
                    <div class="icons">
                        <a href="#" class="fas fa-heart"></a> <a href="#" class="cart-btn">add to cart</a> <a href="#" class="fas fa-share"></a>
                    </div>
                </div>
                <!-- 商品資訊 -->
                <div class="content">
                    <h3> <?php echo($productRow["pTitle"]); ?> </h3>
                    <div class="price"> $<?php echo($productRow["pPrice"]); ?> <span> $<?php echo($productRow["pDiscountProduct"]); ?> </span></div>
                </div>
            </div>
        <?php } } /* [ender] [ender] */ ?>
        </div>

        <!-- 分頁按鈕 -->
        <div class="page-container">
        <?php /* 分頁處理代碼塊 */
        /* 是否有更多頁數 ( _NOW - 2 > 1 ) */
        if (($GlobalValues_Page - 2) > 1) echo("<a href='index.php?page=1'>1 ...</a>");
        for ($tempPage = $GlobalValues_Page - 2; $tempPage <= $GlobalValues_Page + 2; $tempPage++) {
            /* 如果頁數不合法 */
            if ($tempPage < 1 || $tempPage > $productTotalPage) continue;
            /* 如果是當前頁數 */
            if ($tempPage == $GlobalValues_Page) {
                echo("<a class='select-button' href='index.php?page=$tempPage'>$tempPage</a>");
                continue;
            } echo("<a href='index.php?page=$tempPage'>$tempPage</a>");
        }
        /* 是否有更多頁數 ( _NOW + 2 < _MAX ) */
        if (($GlobalValues_Page + 2) < $productTotalPage) echo("<a href='index.php?page=$productTotalPage'>... $productTotalPage</a>");
        ?>
        </div>
    </section>

    <!-- 評論 -->
    <section class="review" id="review">
        <!-- 標題 -->
        <h1 class="heading">買家<span>評論</span> </h1>

        <!-- 評論方框 -->
        <div class="box-container">
        <?php if ($commentResult->num_rows === 0) echo("<div class='nothing-box'><h3>暫時沒有更多的評論<h3></div>"); else { /* 如果沒有任何評論 [header] */ ?>
        <?php while ($commentRow = $commentResult->fetch_assoc()) {  /* 處理顯示隨機三條五星評論 [header] */ ?>
            <div class="box">
                <!-- 五星顯示 -->
                <div class="stars">
                    <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                </div>
                <!-- 評論內容 -->
                <p> <?php echo($commentRow["cMessage"]) ?> </p>
                <!-- 評論客戶資訊 -->
                <div class="account-box">
                    <!-- 客戶頭貼 -->
                    <img src="images/pic-1.png" alt="">
                    <!-- 客戶資訊 -->
                    <div>
                        <h3> <?php echo($commentRow["cName"]) ?> </h3>
                        <span> <?php echo($commentRow["cTime"]) ?> </span>
                    </div>
                </div>
            </div>
        <?php } } /* [footer] [footer] */ ?>
        </div>
    </section>

    <section class="footer">
        <!-- 聲明 -->
        <div class="credit"> Create By <span> Mr. CatHouse </span> | All Rights Reserved </div>
    </section>
</body>
