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
require_once("./utils/LoginDataBaseConnect.php");
$connection = new LoginDataBaseConnect();

session_start();

// 判斷是否為提交資料狀態
if (isset($_POST["UserNameTextBox"]) && isset($_POST["UserPasswordTextBox"])) {
    $resultArray = $connection->tryLogin($_POST["UserNameTextBox"], $_POST["UserPasswordTextBox"]);

    // 判斷登入結果陣列結構
    if ($resultArray[__RETURN_RESULT__] === TRUE) {
        $_SESSION["SESSION_USER"] = $resultArray[__RETURN_CONTENT__];
        header("Location: index.php");
        return;
    }

    // 對登入網頁顯示錯誤訊息框
    $resultContent = $resultArray[__RETURN_CONTENT__];
    header("Location: login.php?error=$resultContent");
}
?>

<!-- 登入網頁 -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入 - 貓之家購物網</title>
    <link rel="shortcut icon" href="#">

    <!-- 連結 -->
    <link rel="stylesheet" type="text/css" href="./login.css">
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>登入帳戶</h1>

        <!-- 接收登入結果訊息 -->
        <?php if (isset($_GET["error"])) {
            $message = $_GET["error"];
            echo ("<div class='messagebox'><h3>$message</h3></div>");
        } ?>

        <!-- 表單 -->
        <form method="POST" action="login.php">
            <!-- 帳戶名稱輸入框 -->
            <div class="field">
                <input type="test" name="UserNameTextBox" title="帳戶名稱" required>
                <span></span>
                <label>帳戶名稱：</label>
            </div>

            <!-- 密碼輸入框 -->
            <div class="field">
                <input type="password" name="UserPasswordTextBox" title="帳戶密碼" required></input>
                <span></span>
                <label>帳戶密碼：</label>
            </div>

            <!-- 送出 -->
            <input type="submit" name="LoginButton" value="登入">
        </form>

        <!-- 跳轉註冊頁面 -->
        <div class="register">您是我們的新朋友嗎？<a href="register.php">馬上註冊！</a></div>
    </div>
</body>

</html>