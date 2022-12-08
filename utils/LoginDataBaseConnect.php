<?php
/**
 * 相關數據表結構：
 * 
 * 資料庫 Account 表資訊
 * 
 * CREATE TABLE Account(
 *  uName varchar(16) PRIMARY KEY NOT NULL, 
 *  uPasswd varchar(32) NOT NULL, 
 *  uUUID varchar(36) NOT NULL,
 *  uTime varchar(20) NOT NULL
 * );
 * 
 * DESCRIBE Account;
 * +---------+-------------+------+-----+---------+-------+
 * | Field   | Type        | Null | Key | Default | Extra |
 * +---------+-------------+------+-----+---------+-------+
 * | uName   | varchar(16) | NO   | PRI | NULL    |       |
 * | uPasswd | varchar(32) | NO   |     | NULL    |       |
 * | uUUID   | varchar(36) | NO   |     | NULL    |       |
 * | uTime   | varchar(20) | NO   |     | NULL    |       |
 * +---------+-------------+------+-----+---------+-------+
 */
require_once("AccountsDataBaseConnect.php");

define("__LOGIN_RESULT__", "__RESULT__");
define("__LOGIN_CONTENT__", "__CONTENT__");

/**
 * 用於登入處理的資料庫類
 */
final class LoginDataBaseConnect extends AccountsDataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 嘗試登入一個帳戶
     * @param string $_Name   登入名稱
     * @param string $_Passwd 登入密碼
     * @return array
     */
    public function tryLogin(string $_Name, string $_Passwd): array
    {
        $returnArray[__LOGIN_RESULT__] = FALSE;
        $returnArray[__REGISTER_CONTENT__] = NULL;

        // 檢查是否為有效數據
        if (empty($_Name) || empty($_Passwd)) {
            $returnArray[__REGISTER_CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnArray;
        }

        // 從資料庫取出相應資料
        $selectAccountResult = $this->getAccount($_Name);
        if ($selectAccountResult->num_rows === 0) {
            $returnArray[__LOGIN_CONTENT__] = "帳戶名稱還沒有被註冊，請問是我們的新朋友嗎？";
            return $returnArray;
        }

        // 處理資料並核對資料是否正確
        $selectAccountRow = $selectAccountResult->fetch_assoc();
        if ($_Name === $selectAccountRow[__ACCOUNT_NAME__] && $_Passwd === $selectAccountRow[__ACCOUNT_PASSWD__]) {
            $returnArray[__LOGIN_RESULT__] = TRUE;
            $returnArray[__LOGIN_CONTENT__] = $selectAccountRow[__ACCOUNT_UUID__];
            return $returnArray;
        }

        // 如果資料核對有錯誤
        $returnArray[__LOGIN_CONTENT__] = "帳戶名稱或密碼輸入錯誤。";
        return $returnArray;
    }
}
?>