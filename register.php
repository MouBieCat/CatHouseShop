<?php
/* PHP 代碼塊 */
require_once("./utils/RegisterDataBaseConnect.php");
$connection = new RegisterDataBaseConnect();

session_start();

// 判斷是否為提交資料狀態
if (isset($_POST["UserNameTextBox"]) && isset($_POST["UserPasswordTextBox"])) {
    $resultArray = $connection->tryRegister($_POST["UserNameTextBox"], $_POST["UserPasswordTextBox"]);

    // 判斷登入結果陣列結構
    if ($resultArray[__REGISTER_RESULT__] === TRUE) {
        header("Location: login.php");
        return;
    }

    // 對註冊網頁顯示錯誤訊息框
    $resultContent = $resultArray[__REGISTER_CONTENT__];
    header("Location: register.php?error=$resultContent");
}
?>

<!-- 註冊網頁 -->
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊 - 貓之家購物網</title>
    <link rel="shortcut icon" href="#">

    <!-- 連結 -->
    <link rel="stylesheet" type="text/css" href="./register.css">
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>註冊帳戶</h1>

        <!-- 接收登入結果訊息 -->
        <?php if (isset($_GET["error"])) {
            $message = $_GET["error"];
            echo ("<div class='messagebox'><h3>$message</h3></div>");
        } ?>

        <!-- 表單 -->
        <form method="POST" action="Register.php">
            <!-- 帳戶名稱輸入框 -->
            <div class="field">
                <input type="text" name="UserNameTextBox" title="帳戶名稱" required>
                <span></span>
                <label>帳戶名稱：</label>
            </div>

            <!-- 密碼輸入框 -->
            <div class="field">
                <input type="password" name="UserPasswordTextBox" title="帳戶密碼" required>
                <span></span>
                <label>帳戶密碼：</label>
            </div>

            <!-- 送出 -->
            <input type="submit" name="RegisterButton" value="註冊">
        </form>

        <!-- 跳轉登入頁面 -->
        <div class="login">已經擁有一個帳戶嗎？<a href="login.php">點擊登入！</a></div>
    </div>
</body>

</html>