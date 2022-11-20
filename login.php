<!-- 登入網頁 -->
<html lang="en" dir="ltr">
<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入 - 貓之家購物網</title>

    <!-- 連結 -->
    <link rel="stylesheet" type="text/css" href="./CommonStyle.css">
    <link rel="stylesheet" type="text/css" href="./Login.css">

    <!-- 代碼 -->
    <?php
    include "DataBaseConnection.php";
    final class LoginDataBaseConnect extends DataBaseConnect {
        /**
         * 資料庫 Accounts 表資訊
         * 
         * CREATE TABLE Accounts(
         *   uName varchar(20) PRIMARY KEY NOT NULL, 
         *   uEmail varchar(64) NOT NULL, 
         *   uPasswd varchar(32) NOT NULL, 
         *   uRegisterTime varchar(20) NOT NULL
         * );
         * 
         * DESCRIBE Accounts;
         * +---------------+-------------+------+-----+---------+-------+
         * | Field         | Type        | Null | Key | Default | Extra |
         * +---------------+-------------+------+-----+---------+-------+
         * | uName         | varchar(20) | NO   | PRI | NULL    |       |
         * | uEmail        | varchar(64) | NO   |     | NULL    |       |
         * | uPasswd       | varchar(32) | NO   |     | NULL    |       |
         * | uRegisterTime | varchar(20) | NO   |     | NULL    |       |
         * +---------------+-------------+------+-----+---------+-------+
         */

        /**
        * 建構子
        */
        public function __construct() {
            parent::__construct();
        }

        /**
         * 嘗試登入
         * @param $_Name   帳戶名稱
         * @param $_Passwd 帳戶密碼
         * @return 提交結果訊息
         */
        public function tryLogin(string $_Name, string $_Passwd) : string {
            // 檢查是否為空值
            if (empty($_Name) || empty($_Passwd)) return "帳戶名稱或是密碼欄位不可為空。";

            // 根據 $_Name 取出帳戶密碼
            $selectPasswdCommand = "SELECT uPasswd FROM Accounts WHERE uName='$_Name'";
            $selectPasswdResult  = $this->m_ConnectObject->query($selectPasswdCommand);

            // 判斷是否有任何的數據
            if ($selectPasswdResult->num_rows === 0) return "帳戶名稱還沒有被註冊，請問是我們的新朋友嗎？";

            // 處理資料並判斷帳戶密碼
            $passwdRow = $selectPasswdResult->fetch_assoc();
            if (strcmp($_Passwd, $passwdRow["uPasswd"]) === 0) 
                return "True";               // 登入成功
            return "帳戶名稱或密碼輸入錯誤。"; // 登入失敗
        }
    }

    // 開啟 SESSION 功能
    session_start();

    // 判斷是否有提交
    if (isset($_POST["UserNameTextBox"]) && isset($_POST["UserPasswordTextBox"])) {
        // 建立資料庫驗證對象
        $connection = new LoginDataBaseConnect();
        $result     = $connection->tryLogin($_POST["UserNameTextBox"], $_POST["UserPasswordTextBox"]);

        // 如果登入成功
        if ($result === "True") {
            $_SESSION["SESSION_USER"] = $_POST["UserNameTextBox"];
            header("Location: index.php");
        } else header("Location: Login.php?error=$result");
    }
    ?>
</head>
<body>
    <div class="container">
        <!-- 標題 -->
        <h1>登入帳戶</h1>

        <!-- 接收登入結果訊息 -->
        <?php if (isset($_GET["error"])) { $msg = $_GET["error"]; echo("<div class='error-messagebox'><h3>$msg</h3></div>"); } ?>

        <!-- 表單 -->
        <form method="POST" action="Login.php">
            <!-- 信箱輸入框 -->
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
