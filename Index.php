<?php
/* PHP 代碼塊 */
require_once("./utils/AccountInfoDataBaseConnect.php");
require_once("./utils/ProductsDataBaseConnect.php");
require_once("./utils/CommentsDataBaseConnect.php");

session_start();

define("__INDEX_PRODUCT_COUNT__", 5);
$__NOW_PAGE = 1;
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
</body>