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
        * @param $_Email    帳戶信箱
        * @param $_Password 帳戶密碼
        * @return 登入結果
        */
        public function tryRegister(string $_Email, string $_Password) : string {
            // 將電子郵件轉為小寫處理
            $_Email = strtolower($_Email);

            // 檢查數據是否為空
            if (empty($_Email) || empty($_Password)) return "電子郵件或密碼欄位不能為空。";

            // 檢查數據使否符合資料庫規格
            if (strlen($_Email) > 64) return "電子郵件長度超出最大長度。";
            if (strlen($_Password) > 32 || strlen($_Password) < 12) return "密碼長度過短或超出最大長度。";

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

            // 插入註冊資料
            $addAccountCommand = "INSERT INTO Accounts (uEMAIL, uPASSWD, uUUID, uTime) VALUES ('$_Email', '$_Password', uuid(), now());";
            $addAccountResult  = mysqli_query($this->m_ConnectObject, $addAccountCommand);
            // 檢查是否插入資料成功
            if (mysqli_errno($this->m_ConnectObject) != 0) return "電子郵件已經被註冊了。";
            
            $this->m_RegisterResult = TRUE;
            return "恭喜您註冊完成，立即<a href='login.php'>登入！</a>";
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
    if (isset($_POST["UserEnmail"]) && isset($_POST["UserPassword"])) {
        // 新增 RegisterConnection 對象
        $connectObject = new RegisterConnection();
        $result        = $connectObject->tryRegister($_POST["UserEnmail"], $_POST["UserPassword"]);

        // 獲取註冊結果
        if ($connectObject->getRegisterResult()) {
            // 跳轉回主網頁
            header("Location: Register.php?success=$result");
        } else header("Location: Register.php?error=$result"); // 顯示失敗訊息
    }
    ?>
</head>

<body>
    <div class="container">
        <!-- 標題 -->
        <h1>註冊帳戶</h1>

        <?php
        // 返回訊息說明
        if (isset($_GET["error"])) {
            $message = $_GET["error"];
            echo("<div class='messagebox-error'><h3>$message</h3></div>");
        }

        // 返回成功訊息
        if (isset($_GET["success"])) {
            $message = $_GET["success"];
            echo("<div class='messagebox-success'><h3>$message</h3></div>");
        }
        ?>

        <!-- 表單 -->
        <form method="POST" action="Register.php">
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
