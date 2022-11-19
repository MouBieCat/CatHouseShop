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
    <link rel="stylesheet" type="text/css" href="./LoginStyle.css">
    <!-- PHP 代碼 -->
    <?php
    include "DataBaseConnection.php";
    final class LoginConnection extends MySQLConnect {
        private $m_LoginResult = false;
            
        /**
        * 建構子
        */
        public function __construct() {
            parent::__construct();
        }

        /**
        * 嘗試登入該帳戶
        * @param $_Email    帳戶信箱
        * @param $_Password 帳戶密碼
        * @return 登入結果
        */
        public function tryLogin(string $_Email, string $_Password) : string {
            // 將電子郵件轉為小寫處理
            $_Email = strtolower($_Email);

            // 檢查數據是否為空
            if (empty($_Email) || empty($_Password)) return "電子郵件或密碼欄位不能為空。";

            /* 
            / ** 表結構 **
            / CREATE TABLE Accounts(
            /    uEMAIL  varchar(64) NOT NULL PRIMARY KEY,
            /    uPASSWD varchar(32) NOT NULL ,
            /    uUUID   varchar(36) NOT NULL ,
            /    uTime   varchar(20) NOT NULL
            / );
            /
            / 顯示結構：DESCRIBE Accounts;
            +---------+-------------+------+-----+---------+-------+
            | Field   | Type        | Null | Key | Default | Extra |
            +---------+-------------+------+-----+---------+-------+
            | uEMAIL  | varchar(64) | NO   | PRI | NULL    |       |
            | uPASSWD | varchar(32) | NO   |     | NULL    |       |
            | uUUID   | varchar(36) | NO   |     | NULL    |       |
            | uTime   | varchar(20) | NO   |     | NULL    |       |
            +---------+-------------+------+-----+---------+-------+
            */

            // 根據電子郵件取出 密碼 UUID
            $findInfoForEmailCommand = "SELECT uPASSWD, uUUID FROM Accounts WHERE uEMAIL='$_Email';";
            $findInfoForEmialResult  = mysqli_query($this->m_ConnectObject, $findInfoForEmailCommand);

            // 是否有找到任何資料
            if ($findInfoForEmialResult->num_rows == 0) return "該電子郵件尚未被註冊。";

            $findInfoRow = mysqli_fetch_assoc($findInfoForEmialResult);
            // 比較輸入密碼與資料庫密碼是否一致
            if (strcmp($_Password, $findInfoRow["uPASSWD"]) == 0) {
                // 比對成功
                $this->m_LoginResult = true;
                return $findInfoRow["uUUID"];
            }

            // 比對失敗
            return "電子郵件或密碼輸入錯誤。";
        }

        /**
        * 獲取是否成功登入
        * @return 是否成功登入
        */
        public function getLoginResult() : bool {
            return $this->m_LoginResult;
        }
    }

    // 開啟 SESSION 功能
    session_start();

    // 判斷是否已經對表單提交
    if (isset($_POST["UserEnmail"]) && isset($_POST["UserPassword"])) {
        // 新增 LoginConnection 對象
        $connectObject = new LoginConnection();
        $result        = $connectObject->tryLogin($_POST["UserEnmail"], $_POST["UserPassword"]);

        // 獲取註冊結果
        if ($connectObject->getLoginResult()) {
            // 寫入當前用戶 UUID
            $_SESSION["SESSION_USER"] = $result;
            // 跳轉回主網頁
            header("Location: index.php");
        } else header("Location: Login.php?error=$result"); // 顯示失敗訊息
    }
    ?>
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>登入帳戶</h1>

        <?php
        // 返回訊息說明
        if (isset($_GET["error"])) {
            $message = $_GET["error"];
            echo("<div class='messagebox-error'><h3>$message</h3></div>");
        }
        ?>

        <!-- 表單 -->
        <form method="POST" action="Login.php">
            <!-- 信箱輸入框 -->
            <div class="field">
                <input type="email" name="UserEnmail" title="帳戶信箱" required>
                <span></span>
                <label>帳戶信箱：</label>
            </div>

            <!-- 密碼輸入框 -->
            <div class="field">
                <input type="password" name="UserPassword" title="帳戶密碼" required></input>
                <span></span>
                <label>帳戶密碼：</label>
            </div>

            <!-- 送出 -->
            <input type="submit" name="login" value="登入">
        </form>

        <!-- 跳轉註冊頁面 -->
        <div class="singup">您是我們的新朋友嗎？<a href="register.php">馬上註冊！</a></div>
    </div>
</body>
</html>
