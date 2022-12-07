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
    <link rel="stylesheet" type="text/css" href="./Login.css">

    <?php /* PHP 代碼塊 */
    include "DataBaseConnection.php";
    final class LoginDataBaseConnect extends DataBaseConnect {
        /**
         * 資料庫 Accounts 表資訊
         * 
         * CREATE TABLE Accounts(
         *   uName             varchar(16) PRIMARY KEY NOT NULL, 
         *   uPasswd           varchar(32)             NOT NULL, 
         *   uUUID             varchar(36)             NOT NULL, 
         *   uRegistrationTime varchar(20)             NOT NULL
         * );
         * 
         * DESCRIBE Accounts;
         * +-------------------+-------------+------+-----+---------+-------+
         * | Field             | Type        | Null | Key | Default | Extra |
         * +-------------------+-------------+------+-----+---------+-------+
         * | uName             | varchar(16) | NO   | PRI | NULL    |       |
         * | uPasswd           | varchar(32) | NO   |     | NULL    |       |
         * | uUUID             | varchar(36) | NO   |     | NULL    |       |
         * | uRegistrationTime | varchar(20) | NO   |     | NULL    |       |
         * +-------------------+-------------+------+-----+---------+-------+
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
        public function tryLogin(string $_Name, string $_Passwd) : array {
            $returnResult["RESULT"] = FALSE; $returnResult["CONTENT"] = NULL;

            // 檢查是否為空值
            if (empty($_Name) || empty($_Passwd)) {
                $returnResult["CONTENT"] = "帳戶名稱或是密碼欄位不可為空。";
                return $returnResult;
            }

            // 根據名稱與密碼取出對應的資料
            $selectAccountCommand = "SELECT uName, uPasswd, uUUID FROM Accounts WHERE uName='$_Name'";
            $selectAccountResult  = $this->m_ConnectObject->query($selectAccountCommand);

            // 判斷是否有任何的數據
            if ($selectAccountResult->num_rows === 0) {
                $returnResult["CONTENT"] = "帳戶名稱還沒有被註冊，請問是我們的新朋友嗎？";
                return $returnResult;
            }

            // 處理資料並判斷帳戶密碼
            $accountRow = $selectAccountResult->fetch_assoc();
            if ($_Name === $accountRow["uName"] && $_Passwd === $accountRow["uPasswd"]) {
                $returnResult["RESULT"] = TRUE;
                $returnResult["CONTENT"] = $accountRow["uUUID"];
                return $returnResult;
            }

            $returnResult["CONTENT"] = "帳戶名稱或密碼輸入錯誤。";
            return $returnResult;
        }
    }

    // 開啟 SESSION 功能
    session_start();

    // 判斷是否有提交
    if (isset($_POST["UserNameTextBox"]) && isset($_POST["UserPasswordTextBox"])) {
        // 建立資料庫驗證對象
        $connection  = new LoginDataBaseConnect();
        $resultArray = $connection->tryLogin($_POST["UserNameTextBox"], $_POST["UserPasswordTextBox"]);

        // 判斷是否登入驗證成功
        if ($resultArray["RESULT"] === TRUE) {
            $_SESSION["SESSION_USER"] = $resultArray["CONTENT"];
            header("Location: index.php");
            return;
        }

        $resultMessage = $resultArray["CONTENT"];
        header("Location: Login.php?error=$resultMessage");
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
