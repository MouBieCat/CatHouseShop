<!-- 註冊網頁 -->
<html lang="en" dir="ltr">
<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊 - 貓之家購物網</title>

    <!-- 連結 -->
    <link rel="stylesheet" type="text/css" href="./commonstyle.css">
    <link rel="stylesheet" type="text/css" href="./Register.css">

    <!-- 代碼 -->
    <?php
    include "DataBaseConnection.php";
    final class RegisterDataBaseConnect extends DataBaseConnect {
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
         * @param $_Email  帳戶信箱
         * @param $_Passwd 帳戶密碼
         * @return 提交結果訊息
         */
        public function tryRegister(string $_Name, string $_Email, string $_Passwd) {
            // 檢查是否為空值
            if (empty($_Name) || empty($_Email) || empty($_Passwd)) return "帳戶名稱、帳戶信箱或是密碼欄位不可為空。";

            // 檢查數據使否符合資料庫規格
            if (strlen($_Name) > 20 || strlen($_Name) < 4) return "帳戶名稱長度過短或超出最大長度。";
            if (strlen($_Email) > 64) return "電子郵件長度超出最大長度。";
            if (strlen($_Passwd) > 32 || strlen($_Passwd) < 12) return "密碼長度過短或超出最大長度。";

            // 開始寫入到數據庫中
            $insertCommand = "INSERT INTO Accounts (uName, uEmail, uPasswd, uRegisterTime) VALUES ('$_Name', '$_Email', '$_Passwd', now());";
            $insertResult  = $this->m_ConnectObject->query($insertCommand);

            // 資料是否插入成功
            if ($insertResult) return "True";
            return "該帳戶名稱已經被使用。";
        }
    }

    // 判斷是否有提交
    if (isset($_POST["UserNameTextBox"]) && isset($_POST["UserEmailTextBox"]) && isset($_POST["UserPasswordTextBox"])) {
        // 建立資料庫驗證對象
        $connection = new RegisterDataBaseConnect();
        $result     = $connection->tryRegister($_POST["UserNameTextBox"], $_POST["UserEmailTextBox"], $_POST["UserPasswordTextBox"]);

        // 如果註冊成功
        if ($result === "True") {
            header("Location: login.php");
        } else header("Location: Register.php?error=$result");
    }
    ?>
</head>
<body>
    <div class="container">
        <!-- 標題 -->
        <h1>註冊帳戶</h1>

        <!-- 接收登入結果訊息 -->
        <?php if (isset($_GET["error"])) { $msg = $_GET["error"]; echo("<div class='error-messagebox'><h3>$msg</h3></div>"); } ?>

        <!-- 表單 -->
        <form method="POST" action="Register.php">
            <!-- 帳戶名稱輸入框 -->
            <div class="field">
                <input type="text" name="UserNameTextBox" title="帳戶名稱" required>
                <span></span>
                <label>帳戶名稱：</label>
            </div>

            <!-- 信箱輸入框 -->
            <div class="field">
                <input type="email" name="UserEmailTextBox" title="帳戶信箱" required>
                <span></span>
                <label>帳戶信箱：</label>
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
