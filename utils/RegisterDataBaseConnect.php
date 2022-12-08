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

final class RegisterDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 判斷該帳戶名稱是否已經被使用
     * @param string $_Name 帳戶名稱
     * @return bool
     */
    public function isRegister(string $_Name): bool
    {
        $selectAccountNameCommand = "SELECT uName FROM Accounts WHERE uName='$_Name';";
        $selectAccountResult = $this->m_ConnectObject->query($selectAccountNameCommand);
        return $selectAccountResult !== 0;
    }

    /**
     * 嘗試註冊一個帳戶
     * @param string $_Name   註冊名稱
     * @param string $_Passwd 註冊密碼
     * @return array
     */
    public function tryRegister(string $_Name, string $_Passwd): array
    {
        $returnResult[__RESULT__] = FALSE;
        $returnResult[__CONTENT__] = NULL;

        // 檢查是否為空值
        if (empty($_Name) || empty($_Passwd)) {
            $returnResult[__CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnResult;
        }

        // 檢查數據使否符合資料庫規格
        if (strlen($_Name) > 16 || strlen($_Name) < 4) {
            $returnResult[__CONTENT__] = "帳戶名稱長度過短或超出最大長度。";
            return $returnResult;
        }
        if (strlen($_Passwd) > 32 || strlen($_Passwd) < 12) {
            $returnResult[__CONTENT__] = "密碼長度過短或超出最大長度。";
            return $returnResult;
        }

        // 插入資料並判斷是否成功插入資料
        $insertAccountCommand = "INSERT INTO Accounts(uName, uPasswd, uUUID, uRegistrationTime) VALUES ('$_Name', '$_Passwd', uuid(), now());";
        $insertAccountResult = $this->m_ConnectObject->query($insertAccountCommand);
        if ($insertAccountResult) {
            $returnResult[__RESULT__] = TRUE;
            return $returnResult;
        }

        // // 如果資料插入失敗
        $returnResult[__CONTENT__] = "該帳戶名稱已經被使用。";
        return $returnResult;
    }
}
?>