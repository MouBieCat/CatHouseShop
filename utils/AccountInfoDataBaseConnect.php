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
 * CREATE TABLE AccountInfo(
 *  iUUID varchar(36) PRIMARY KEY NOT NULL, 
 *  iPhone varchar(15) NULL, 
 *  iEmail varchar(64) NULL, iAlias varchar(16) NOT NULL DEFAULT "Member", 
 *  iImageSrc varchar(64) NOT NULL DEFAULT "./accounts/default.jpg"
 * );
 * 
 * DESCRIBE AccountInfo;
 * +-----------+-------------+------+-----+---------------------------+-------+
 * | Field     | Type        | Null | Key | Default                   | Extra |
 * +-----------+-------------+------+-----+---------------------------+-------+
 * | iUUID     | varchar(36) | NO   | PRI | NULL                      |       |
 * | iPhone    | varchar(15) | YES  |     | NULL                      |       |
 * | iEmail    | varchar(64) | YES  |     | NULL                      |       |
 * | iAlias    | varchar(16) | NO   |     | Member                    |       |
 * | iImageSrc | varchar(64) | NO   |     | ./accounts/default.jpg    |       |
 * +-----------+-------------+------+-----+---------------------------+-------+
 */
require_once("DataBaseConnection.php");

define("__ACCOUNTINFO_UUID__", "iUUID");
define("__ACCOUNTINFO_PHONE__", "iPhone");
define("__ACCOUNTINFO_EMAIL__", "iEmail");
define("__ACCOUNTINFO_ALIAS__", "iAlias");
define("__ACCOUNTINFO_IMAGE__", "iImageSrc");

/**
 * 有關帳戶資訊資料庫操作類
 */
final class AccountInfoDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 獲取所有帳戶資訊資料
     * @return mysqli_result
     */
    public function getAccountInfos(): mysqli_result
    {
        $selectAccountInfosCommand = "SELECT * FROM AccountInfo;";
        return $this->m_ConnectObject->query($selectAccountInfosCommand);
    }

    /**
     * 獲取指定帳戶資訊資料
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getAccountInfo(string $_UUID): mysqli_result
    {
        $selectAccountInfoCommand = "SELECT * FROM AccountInfo WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        return $this->m_ConnectObject->query($selectAccountInfoCommand);
    }

    /**
     * 添加一筆帳戶資訊資料
     * @param string $_UUID 標識碼
     * @return bool
     */
    public function addAccountInfo(string $_UUID): bool
    {
        $insertAccountInfoCommand = "INSERT INTO AccountInfo (" . __ACCOUNTINFO_UUID__ . ") VALUES ('$_UUID');";
        return $this->m_ConnectObject->query($insertAccountInfoCommand);
    }

    /**
     * 刪除一筆帳戶資訊資料
     * @param string $_UUID 標識碼
     * @return void
     */
    public function removeAccountInfo(string $_UUID): void
    {
        $deleteAccountInfoCommand = "DELETE FROM AccountInfo WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($deleteAccountInfoCommand);
    }

    public function setAccountInfoPhone(string $_UUID, string $_Phone): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否符合資料庫規格
        if (strlen($_Phone) > 15) {
            $returnArray[__RETURN_CONTENT__] = "電話號碼長度不符合規定。";
            return $returnArray;
        }

        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_PHONE__ . "='$_Phone' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);

        $returnArray[__RETURN_RESULT__] = TRUE;
        return $returnArray;
    }

    public function setAccountInfoEmail(string $_UUID, string $_Email): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否符合資料庫規格
        if (strlen($_Email) > 64) {
            $returnArray[__RETURN_CONTENT__] = "電子信箱長度不符合規定。";
            return $returnArray;
        }

        $emailLower = strtolower($_Email);
        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_EMAIL__ . "='$emailLower' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);

        $returnArray[__RETURN_RESULT__] = TRUE;
        return $returnArray;
    }

    public function setAccountInfoAlias(string $_UUID, string $_Alias): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否符合資料庫規格
        if (strlen($_Alias) > 16 || strlen($_Alias) < 4) {
            $returnArray[__RETURN_CONTENT__] = "帳戶暱稱長度不符合規定。";
            return $returnArray;
        }

        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_ALIAS__ . "='$_Alias' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);

        $returnArray[__RETURN_RESULT__] = TRUE;
        return $returnArray;
    }

    public function setAccountInfoImage(string $_UUID, string $_ImageSrc): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否符合資料庫規格
        if (strlen($_ImageSrc) > 64) {
            $returnArray[__RETURN_CONTENT__] = "帳戶頭貼路徑長度不符合規定。";
            return $returnArray;
        }

        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_IMAGE__ . "='$_ImageSrc' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);

        $returnArray[__RETURN_RESULT__] = TRUE;
        return $returnArray;
    }
}
?>