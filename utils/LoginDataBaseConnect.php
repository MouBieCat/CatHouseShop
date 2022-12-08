<?php
/**
 * 相關數據表結構：
 * 
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
include "DataBaseConnection.php";

define("__RESULT__", "__RETURN__RESULT__");
define("__CONTENT__", "__RETURN__CONTENT__");

/**
 * 用於登入處理的資料庫類
 */
final class LoginDataBaseConnect extends DataBaseConnect
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
        $returnResult[__RESULT__] = FALSE;
        $returnResult[__CONTENT__] = NULL;

        // 檢查是否為空值
        if (empty($_Name) || empty($_Passwd)) {
            $returnResult[__CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnResult;
        }

        // 根據名稱與密碼取出對應的資料
        $selectAccountCommand = "SELECT uName, uPasswd, uUUID FROM Accounts WHERE uName='$_Name';";
        $selectAccountResult = $this->m_ConnectObject->query($selectAccountCommand);

        // 判斷是否有任何的數據
        if ($selectAccountResult->num_rows === 0) {
            $returnResult[__CONTENT__] = "帳戶名稱還沒有被註冊，請問是我們的新朋友嗎？";
            return $returnResult;
        }

        // 處理資料並判斷帳戶密碼
        $accountRow = $selectAccountResult->fetch_assoc();
        if ($_Name === $accountRow["uName"] && $_Passwd === $accountRow["uPasswd"]) {
            $returnResult[__RESULT__] = TRUE;
            $returnResult[__CONTENT__] = $accountRow["uUUID"];
            return $returnResult;
        }

        // 如果驗證帳戶失敗
        $returnResult[__CONTENT__] = "帳戶名稱或密碼輸入錯誤。";
        return $returnResult;
    }
}
?>