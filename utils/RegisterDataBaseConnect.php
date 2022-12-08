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
require_once("AccountsDataBaseConnect.php");

define("__REGISTER_RESULT__", "__RESULT__");
define("__REGISTER_CONTENT__", "__CONTENT__");

/**
 * 用於註冊處理的資料庫類
 */
final class RegisterDataBaseConnect extends AccountsDataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 嘗試註冊一個帳戶
     * @param string $_Name   註冊名稱
     * @param string $_Passwd 註冊密碼
     * @return array
     */
    public function tryRegister(string $_Name, string $_Passwd): array
    {
        $returnResult[__REGISTER_RESULT__] = FALSE;
        $returnResult[__REGISTER_CONTENT__] = NULL;

        // 檢查是否為空值
        if (empty($_Name) || empty($_Passwd)) {
            $returnResult[__REGISTER_CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnResult;
        }

        // 檢查數據使否符合資料庫規格
        if (strlen($_Name) > 16 || strlen($_Name) < 4) {
            $returnResult[__REGISTER_CONTENT__] = "帳戶名稱長度過短或超出最大長度。";
            return $returnResult;
        }
        if (strlen($_Passwd) > 32 || strlen($_Passwd) < 12) {
            $returnResult[__REGISTER_CONTENT__] = "密碼長度過短或超出最大長度。";
            return $returnResult;
        }

        // 插入資料並判斷是否成功插入資料
        $insertAccountResult = $this->addAccount($_Name, $_Passwd);
        if ($insertAccountResult) {
            $returnResult[__REGISTER_RESULT__] = TRUE;
            return $returnResult;
        }

        // 如果資料插入失敗
        $returnResult[__REGISTER_CONTENT__] = "該帳戶名稱已經被使用。";
        return $returnResult;
    }
}
?>