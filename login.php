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
        * @param $_ID       帳戶名稱
        * @param $_Password 帳戶密碼
        * @return 登入結果
        */
        public function tryLogin(string $_ID, string $_Password) : string {
            /* 
            CREATE TABLE Accounts (
                uID varchar(20) PRIMARY KEY NOT NULL, 
                uEMAIL varchar(64) NOT NULL, 
                uPASSWD varchar(32) NOT NULL, 
                uTIME varchar(20) NOT NULL
            );

            -> DESCRIBE Accounts;
            +---------+-------------+------+-----+---------+-------+
            | Field   | Type        | Null | Key | Default | Extra |
            +---------+-------------+------+-----+---------+-------+
            | uID     | varchar(20) | NO   | PRI | NULL    |       |
            | uEMAIL  | varchar(64) | NO   |     | NULL    |       |
            | uPASSWD | varchar(32) | NO   |     | NULL    |       |
            | uTIME   | varchar(20) | NO   |     | NULL    |       |
            +---------+-------------+------+-----+---------+-------+
            */

            // 檢查數據是否為空
            if (empty($_ID) || empty($_Password)) return "帳戶名稱、密碼欄位不能為空。";

            // 根據帳戶名稱取出密碼
            $selectPasswordForNameCommand = "SELECT uPASSWD FROM Accounts WHERE uID='$_ID';";
            $selectPasswordForNameResult  = mysqli_query($this->m_ConnectObject, $selectPasswordForNameCommand);

            // 是否有找到任何資料
            if ($selectPasswordForNameResult->num_rows == 0) return "該帳戶名稱尚未被註冊。";

            // 處理資料
            $selectPasswordRow = mysqli_fetch_assoc($selectPasswordForNameResult);
            // 比較輸入密碼與資料庫密碼是否一致
            if (strcmp($_Password, $selectPasswordRow["uPASSWD"]) == 0) {
                // 比對成功
                $this->m_LoginResult = true;
                return "";
            } return "帳戶名稱或密碼輸入錯誤。"; // 比對失敗
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
    if (isset($_POST["UserName"]) && isset($_POST["UserPassword"])) {
        // 新增 LoginConnection 對象
        $connectObject = new LoginConnection();
        $result        = $connectObject->tryLogin($_POST["UserName"], $_POST["UserPassword"]);

        // 獲取登入結果
        if ($connectObject->getLoginResult()) {
            $_SESSION["SESSION_USER"] = $_POST["UserName"];
            header("Location: Index.php");
        }
        else 
            header("Location: Login.php?error=$result");    // 顯示失敗訊息
    }
    ?>
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>登入帳戶</h1>

        <?php
        if (isset($_GET["error"])) {                                        // 返回訊息說明
            $message = $_GET["error"];
            echo("<div class='messagebox-error'><h3>$message</h3></div>");
        }
        ?>

        <!-- 表單 -->
        <form method="POST" action="Login.php">
            <!-- 信箱輸入框 -->
            <div class="field">
                <input type="test" name="UserName" title="帳戶名稱" required>
                <span></span>
                <label>帳戶名稱：</label>
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
    
    <!-- 網頁尾部公司聲明 -->
	<div class="copyright">
		<p>© 2022 by CatHouse. Just a practice template</p>
	</div>
</body>
</html>
