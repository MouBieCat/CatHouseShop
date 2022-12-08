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
        // 返回結果
        $returnArray[__REGISTER_RESULT__] = FALSE;
        $returnArray[__REGISTER_CONTENT__] = NULL;

        // 檢查是否為有效數據
        if (empty($_Name) || empty($_Passwd)) {
            $returnArray[__REGISTER_CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnArray;
        }

        // 檢查是否符合資料庫規格
        if (strlen($_Name) > 16 || strlen($_Name) < 4) {
            $returnArray[__REGISTER_CONTENT__] = "帳戶名稱長度過短或超出最大長度。";
            return $returnArray;
        }
        if (strlen($_Passwd) > 32 || strlen($_Passwd) < 6) {
            $returnArray[__REGISTER_CONTENT__] = "密碼長度過短或超出最大長度。";
            return $returnArray;
        }

        // 插入數據到資料庫並判斷資料是否插入成功
        if ($this->addAccount($_Name, $_Passwd)) {
            $returnArray[__REGISTER_RESULT__] = TRUE;
            return $returnArray;
        }

        // 如果資料插入失敗
        $returnArray[__REGISTER_CONTENT__] = "該帳戶名稱已經被使用。";
        return $returnArray;
    }
}
?>