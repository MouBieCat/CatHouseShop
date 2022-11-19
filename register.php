<!-- 註冊網頁 -->
<html lang="en" dir="ltr">
<head>
    <!-- 網頁元素 -->
    <meta charset="UTF-8">
    <meta http-equiv="X-YA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊 - 貓之家購物網</title>
    <!-- 連結 -->
    <link rel="stylesheet" type="text/css" href="./CommonStyle.css">
    <link rel="stylesheet" type="text/css" href="./RegisterStyle.css">
    <!-- PHP 代碼 -->
    <?php
    include "DataBaseConnection.php";
    final class RegisterConnection extends MySQLConnect {
    private $m_RegisterResult = false;

        /**
        * 建構子
        */
        public function __construct() {
            parent::__construct();
        }

        /**
        * 嘗試註冊該帳戶
        * @param $_ID       帳戶名稱
        * @param $_Email    帳戶信箱
        * @param $_Password 帳戶密碼
        * @return 登入結果
        */
        public function tryRegister(string $_ID, string $_Email, string $_Password) : string {
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

            // 將電子郵件轉為小寫處理
            $_Email = strtolower($_Email);

            // 檢查數據是否為空
            if (empty($_ID) || empty($_Email) || empty($_Password)) return "帳戶名稱、電子郵件、密碼欄位不能為空。";

            // 檢查數據使否符合資料庫規格
            if (strlen($_ID) > 20 || strlen($_Password) < 4) return "帳戶名稱長度過短或超出最大長度。";
            if (strlen($_Email) > 64) return "電子郵件長度超出最大長度。";
            if (strlen($_Password) > 32 || strlen($_Password) < 12) return "密碼長度過短或超出最大長度。";

            // 插入註冊資料
            $insertAccountCommand = "INSERT INTO Accounts (uID, uEMAIL, uPASSWD, uTIME) VALUES ('$_ID', '$_Email', '$_Password', now());";
            $insertAccountResult  = mysqli_query($this->m_ConnectObject, $insertAccountCommand);
            // 檢查是否插入資料成功
            if (mysqli_errno($this->m_ConnectObject) != 0) return "該帳戶名稱已經被使用。";
            
            $this->m_RegisterResult = TRUE;
            return "";
        }

        /**
        * 獲取是否成功登入
        * @return 是否成功註冊
        */
        public function getRegisterResult() : bool {
            return $this->m_RegisterResult;
        }
    }
    
    // 開啟 SESSION 功能
    session_start();

    // 判斷是否已經對表單提交
    if (isset($_POST["UserName"]) && isset($_POST["UserEnmail"]) && isset($_POST["UserPassword"])) {
        // 新增 RegisterConnection 對象
        $connectObject = new RegisterConnection();
        $result        = $connectObject->tryRegister($_POST["UserName"], $_POST["UserEnmail"], $_POST["UserPassword"]);

        // 獲取註冊結果
        if ($connectObject->getRegisterResult()) 
            header("Location: Login.php");
        else 
            header("Location: Register.php?error=$result");    // 顯示失敗訊息
    }
    ?>
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>註冊帳戶</h1>

        <?php
        if (isset($_GET["error"])) {                                        // 返回訊息說明
            $message = $_GET["error"];
            echo("<div class='messagebox-error'><h3>$message</h3></div>");
        }
        ?>

        <!-- 表單 -->
        <form method="POST" action="Register.php">
            <!-- 帳戶名稱輸入框 -->
            <div class="field">
                <input type="text" name="UserName" title="帳戶名稱" required>
                <span></span>
                <label>帳戶名稱：</label>
            </div>

            <!-- 信箱輸入框 -->
            <div class="field">
                <input type="email" name="UserEnmail" title="帳戶信箱" required>
                <span></span>
                <label>帳戶信箱：</label>
            </div>

            <!-- 密碼輸入框 -->
            <div class="field">
                <input type="password" name="UserPassword" title="帳戶密碼" required>
                <span></span>
                <label>帳戶密碼：</label>
            </div>

            <!-- 送出 -->
            <input type="submit" name="login" value="註冊">
        </form>

        <!-- 跳轉登入頁面 -->
        <div class="singup">已經擁有一個帳戶嗎？<a href="login.php">點擊登入！</a></div>
    </div>
</body>
</html>
