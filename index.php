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

	/* Index define. */
	define("__PAGE_PRODUCT_SIZE__", 12); // 每頁顯示比數
	define("__DEFAULT_PAGE__", 1);      // 預設開啟頁數

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
		public function getProducts(int $_Page = __DEFAULT_PAGE__, int $_Size = __PAGE_PRODUCT_SIZE__) {
			$startIndex = ($_Page - 1) * $_Size;
			$selectProductsCommand = "SELECT * FROM Product LIMIT $startIndex, $_Size"; 
			return mysqli_query($this->m_ConnectObject, $selectProductsCommand);
		}

		/**
		 * 取出所有資料
		 * @return 資料庫查詢結果
		 */
		public function getAllProducts() {
			$selectProductsCommand = "SELECT * FROM Product"; 
			return mysqli_query($this->m_ConnectObject, $selectProductsCommand);
		}
	}

	// 處理頁數
	$nowPage = __DEFAULT_PAGE__;
	if (isset($_GET["page"])) $nowPage = $_GET["page"];

	// 查詢相關物件
	$indexProduct = new IndexProductConnection();
	// 取出所有資料
	$allProductResult = $indexProduct->getAllProducts();
	$totalPage        = ceil($allProductResult->num_rows / __PAGE_PRODUCT_SIZE__);

    // 取出特定位置資料
    $partialProductResult = $indexProduct->getProducts($nowPage);
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
        <div class="product-container">
        <?php while ($rowProduct = mysqli_fetch_assoc($partialProductResult)) {  // 商品顯示處理代碼 (HEAD) ?>
            <!-- 商品資訊框 -->
            <div class="box">
                <img alt="ProductImage" src=<?php echo $rowProduct["pIMAGE"]; ?>>
                <h4> <?php echo $rowProduct["pTITLE"]; ?> </h4>
                <h5>TWD <?php echo $rowProduct["pPRICE"]; ?>$</h5>
                <div class="cart">
                    <a href=<?php echo "Product.php?product=".$rowProduct["pUUID"]; ?>><i class='bx bx-cart' ></i></a>
                </div>
            </div>
        <?php } // 商品顯示處理代碼 (END) ?>
        </div>
	</section>

	<!-- 翻頁按鈕 -->
	<div class="page-container">
		<!-- < -->
        <a href=<?php if ($nowPage > 1) echo "index.php?page=".($nowPage - 1); /* 往前翻頁處理 */ ?>><button class="right-button"><svg enable-background="new 0 0 11 11" viewBox="0 0 11 11" x="0" y="0" class="shopee-svg-icon icon-arrow-left"><g><path d="m8.5 11c-.1 0-.2 0-.3-.1l-6-5c-.1-.1-.2-.3-.2-.4s.1-.3.2-.4l6-5c .2-.2.5-.1.7.1s.1.5-.1.7l-5.5 4.6 5.5 4.6c.2.2.2.5.1.7-.1.1-.3.2-.4.2z"></svg></button></a>
        <?php
        /* 如果不會顯示第一頁，則用分隔點並且顯示第一頁 */
        if ($nowPage - 2 > 1) echo "<a href='index.php?page=1'><button class='noselect-button'>1</button></a><button class='none-button'>...</button>";
        /* 頁數按鈕處理代碼 */
        for ($pageTemp = $nowPage - 2; $pageTemp < $nowPage + 3; $pageTemp++) {
            if ($pageTemp < 1 || $pageTemp > $totalPage) continue;
            if ($pageTemp == $nowPage) echo "<button class='select-button'>$pageTemp</button>";
            else echo "<a href='index.php?page=$pageTemp'><button class='noselect-button'>$pageTemp</button></a>";
        }
        /* 如果不會顯示最後頁，則用分隔點並且顯示最後頁 */
        if ($nowPage + 3 < $totalPage) echo "<button class='none-button'>...</button><a href='index.php?page=$totalPage'><button class='noselect-button'>$totalPage</button></a>";?>
        <!-- >  -->
	    <a href=<?php if ($nowPage < $totalPage) echo "index.php?page=".($nowPage + 1) /* 往後翻頁處理 */ ?>><button class="left-button"><svg enable-background="new 0 0 11 11" viewBox="0 0 11 11" x="0" y="0" class="shopee-svg-icon icon-arrow-right"><path d="m2.5 11c .1 0 .2 0 .3-.1l6-5c .1-.1.2-.3.2-.4s-.1-.3-.2-.4l-6-5c-.2-.2-.5-.1-.7.1s-.1.5.1.7l5.5 4.6-5.5 4.6c-.2.2-.2.5-.1.7.1.1.3.2.4.2z"></svg></button></a>
	</div>
	
	<!-- 連接 -->
	<script src="IndexScript.js"></script>
</body>
</html>