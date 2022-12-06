<!-- 主網頁 -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁 - 貓之家購物網</title>

    <!-- 連結 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./Index.css">
    <?php
    /* <!-- 代碼 --> */
    include "DataBaseConnection.php";
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
        public function getComments(int $_Count = 3) {
            $selectCommand = "SELECT * FROM Comments WHERE cStars=5 order by rand() LIMIT $_Count";
            return $this->m_ConnectObject->query($selectCommand);
        }
    }

    $commentConnection = new CommentDataBaseConnect();
    $commentResult     = $commentConnection->getComments();
    ?>
</head>
<body>
    <!-- 工具列 --> 
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
            <h3>鮮花</h3>
            <span>天然及美麗的花</span>
            <p>油菜花四片花瓣，整齊地圍繞著花蕊，樸實個性。花瓣十分精致，有細細的紋路，那是技藝多么高超的雕刻家也無法雕琢出來的。中間的花蕊彎曲著湊在一塊，仿佛在說著悄悄話。它有粗壯的根莖，茂密的葉，有著像栽種它們的農民們一樣的淳樸與粗獷。</p>
        </div>
    </section>
    
    <!-- 關於我們 -->
    <section class="about" id="about">
        <h1 class="heading"> <span>關於</span>我們</h1>
        <div class="row">
            <!-- 影片 -->
            <div class="video-container">
                <video src="./Resource/Index-AboutVideo.mp4" loop autoplay muted></video>
                <h3>Best Flower Seller</h3>
            </div>
            <!-- 主內容 -->
            <div class="content">
                <h3>為什麼該選擇我們？</h3>
                <p>我們是您最佳的花供應商，提供最優質芳香的花朵。一切以顧客至上為方針，並且致力於種植最優秀的花朵，讓花朵完美的送到您的手中！</p>
                <p>最後感謝您選擇我們，我們將會做到比現在更好的服務！</p>
            </div>
        </div>
    </section>

    <!-- 評論 -->
    <section class="review" id="review">
        <h1 class="heading">買家<span>評論</span> </h1>
        <!-- 評論方框 -->
        <div class="box-container">
        <?php while ($commentRow = $commentResult->fetch_assoc()) {  /* 處理顯示隨機三條五星評論 */ ?>
            <div class="box">
                <!-- 五星顯示 -->
                <div class="stars">
                    <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i> <i class="fas fa-star"></i>
                </div>
                <!-- 評論內容 -->
                <p> <?php echo($commentRow["cMessage"]) ?> </p>
                <!-- 評論庫戶資訊 -->
                <div class="user">
                    <!-- 庫戶頭貼 -->
                    <img src="images/pic-1.png" alt="">
                    <!-- 庫戶資訊 -->
                    <div class="user-info">
                        <h3> <?php echo($commentRow["cName"]) ?> </h3>
                        <span> <?php echo($commentRow["cTime"]) ?> </span>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </section>
</body>
