<!--

模板來源：https://www.youtube.com/watch?v=tHJI0Lbd77E&ab_channel=TahmidAhmed
我們沒有對該模板任何代碼進行編寫，我們只負責修改它。請支持並重視原作者的付出及努力。

Template source: https://www.youtube.com/watch?v=tHJI0Lbd77E&ab_channel=TahmidAhmed
We did not write any code for this template, 
we were only responsible for modifying it. 
Please support and value the dedication and efforts of the original author.

-->

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
	<link rel="stylesheet" type="text/css" href="./CommonStyle.css">
    <link rel="stylesheet" type="text/css" href="./IndexStyle.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<?php
	include "DataBaseConnection.php";
	final class IndexProductConnection extends MySQLConnect {
		/**
		* 建構子
		*/
		public function __construct() {
			parent::__construct();
		}
	
		/**
		 * 取出網頁所需的資料庫資料
		 * @param $_Page 頁數  (我們會對頁數進行 -1)
		 * @param $_Size 資料量
		 * @return 資料庫查詢結果
		 */
		public function getProducts(int $_Page = 1, int $_Size = 12) {
			$startIndex = ($_Page - 1) * $_Size;                                                              // 計算資料開始索引
			$selectProductsCommand = "SELECT pUUID, pTITLE, pPRICE, pIMAGE FROM Product LIMIT $startIndex, $_Size";  // 指令
			return mysqli_query($this->m_ConnectObject, $selectProductsCommand);                              // 查詢
		}
	}
	
	// 獲取頁數
	$page = 1;
	if (isset($_GET["page"])) $page = $_GET["page"];

	// 查詢相關物件
	$indexProduct       = new IndexProductConnection($page);
	$indexProductResult = $indexProduct->getProducts();
	?>
</head>

<body>
	<!-- 網頁頭欄位條 -->
	<header>
		<!-- 商標 -->
		<a href="#" class="logo">Cat House</a>

		<!-- 當網頁縮小顯示更小的條目欄 -->
		<div class="bx bx-menu" id="menu-icon"></div>
		
		<!-- 欄位按鈕 -->
		<ul class="navbar">
            <li><a href="#shop">商品列表</a></li>
            <li><a href="#about">關於我們</a></li>
		</ul>

		<!-- 功能按鈕 -->
		<div class="icons">
            <a href="#"><i class='bx bx-search'></i></a>
            <a href="#"><i class='bx bxs-user-circle' ></i></a>
            <a href="#"><i class='bx bxs-shopping-bag' ></i></a>
		</div>
	</header>

	<!-- 商品列表清單 -->
	<section class="shop" id="shop">
        <div class="container">
        <?php while ($rowProduct = mysqli_fetch_assoc($indexProductResult)) {  // 商品顯示處理代碼 (HEAD) ?>
            <!-- 商品資訊框 -->
            <div class="box">
                <img src=<?php echo $rowProduct["pIMAGE"]; ?>>
                <h4> <?php echo $rowProduct["pTITLE"]; ?> </h4>
                <h5>TWD <?php echo $rowProduct["pPRICE"]; ?>$</h5>
                <div class="cart">
                    <a href=<?php echo "Product.php?product=".$rowProduct["pUUID"]; ?>><i class='bx bx-cart' ></i></a>
                </div>
            </div>
        <?php } // 商品顯示處理代碼 (END) ?>
        </div>
	</section>

	<!-- 關於我們 -->
	<section class="about" id="about">
        <div class="about-content">
            <h2>關於我們</h2>
            <p>我們不是一個有效的買賣商家，該網頁只是一個大學作品。用於在未來很好的觀摩以及反覆練習。模板來源：
                <a href="https://www.youtube.com/watch?v=tHJI0Lbd77E&ab_channel=TahmidAhmed">點我前往</a>
            </p>
        </div>
	</section>

	<!-- 網頁尾部快捷按鈕 -->
	<section class="contact" id="contact">
        <div class="main-contact">
            <!-- 第一列 -->
            <div class="contact-content">
                <li><a href="#shop">商品列表</a></li>
                <li><a href="#about">關於我們</a></li>
            </div>
            <!-- 第二列 -->
            <div class="contact-content">
                <li><a href="https://www.facebook.com/" target="_blank">Facebook</a></li>
                <li><a href="https://www.instagram.com/" target="_blank">Instagram</a></li>
            </div>
        </div>
	</section>

	<!-- 網頁尾部網頁聲明 -->
	<div class="copyright">
		<p>© 2022 by CatHouse. Just a practice template</p>
	</div>

	<!-- 連接 -->
	<script src="IndexScript.js"></script>
</body>
</html>