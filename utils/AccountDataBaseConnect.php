<?php
/**
 * Copyright (C) 2022 MouBieCat
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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
require_once("DataBaseConnection.php");

define("__ACCOUNT_NAME__", "uName");
define("__ACCOUNT_PASSWD__", "uPasswd");
define("__ACCOUNT_UUID__", "uUUID");
define("__ACCOUNT_TIME__", "uTime");

/**
 * 有關帳戶表資料庫連接類
 */
class AccountDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加一比帳戶
     * @param string $_Name     帳戶名稱
     * @param string $_Password 帳戶密碼
     * @return bool
     */
    protected function addAccount(string $_Name, string $_Password): bool
    {
        $insertAccountCommand = "INSERT INTO Account (" . __ACCOUNT_NAME__ . ", " . __ACCOUNT_PASSWD__ . ", " . __ACCOUNT_UUID__ . ", " . __ACCOUNT_TIME__ . ") VALUES ('$_Name', '$_Password', uuid(), now());";
        return $this->m_ConnectObject->query($insertAccountCommand);
    }

    /**
     * 刪除一筆帳戶資料
     * @param string $_Name 帳戶名稱
     * @return void
     */
    public function removeAccount(string $_Name): void
    {
        $deleteAccountCommand = "DELETE FROM Account WHERE " . __ACCOUNT_NAME__ . "='$_Name';";
        $this->m_ConnectObject->query($deleteAccountCommand);
    }

    /**
     * 獲取指定帳戶資料
     * @param string $_Name 帳戶名稱
     * @return mysqli_result
     */
    public function getAccount(string $_Name): mysqli_result
    {
        $selectAccountCommand = "SELECT * FROM Account WHERE " . __ACCOUNT_NAME__ . "='$_Name';";
        return $this->m_ConnectObject->query($selectAccountCommand);
    }

    /**
     * 該標識碼是否已經被帳戶生成
     * @param string $_UUID
     * @return bool
     */
    public function isGeneratedUUID(string $_UUID): bool
    {
        $selectAccountResult = $this->getAccountOfUUID($_UUID);
        return ($selectAccountResult->num_rows !== 0);
    }

    /**
     * 根據該標識碼獲取帳戶
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getAccountOfUUID(string $_UUID): mysqli_result
    {
        $selectAccountCommand = "SELECT * FROM Account WHERE " . __ACCOUNT_UUID__ . "='$_UUID';";
        return $this->m_ConnectObject->query($selectAccountCommand);
    }

    public function setNewPassword(string $_UUID, string $_OldPasswd, string $_NewPasswd): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否存在該帳戶
        $accountResult = $this->getAccountOfUUID($_UUID);
        if ($accountResult->num_rows === 0) {
            $returnArray[__RETURN_CONTENT__] = "該帳戶不存在";
            return $returnArray;
        }

        // 檢查密碼是否正確
        $accountRow = $accountResult->fetch_assoc();
        if ($accountRow[__ACCOUNT_PASSWD__] !== $_OldPasswd) {
            $returnArray[__RETURN_CONTENT__] = "與原密碼不符合，無法修改為新密碼。";
            return $returnArray;
        }

        $updateAccountCommand = "UPDATE Account SET " . __ACCOUNT_PASSWD__ . "='$_NewPasswd' WHERE " . __ACCOUNT_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountCommand);

        $returnArray[__RETURN_RESULT__] = TRUE;
        return $returnArray;
    }
}
?>