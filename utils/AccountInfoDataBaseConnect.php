<?php
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
     * @return void
     */
    public function addAccountInfo(string $_UUID): void
    {
        $insertAccountInfoCommand = "INSERT INTO AccountInfo (" . __ACCOUNTINFO_UUID__ . ") VALUES ('$_UUID');";
        $this->m_ConnectObject->query($insertAccountInfoCommand);
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

    public function setAccountInfoPhone(string $_UUID, string $_Phone): string
    {
        // 檢查是否符合資料庫規格
        if (strlen($_Phone) > 15)
            return "電話號碼長度不符合規定。";
        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_PHONE__ . "='$_Phone' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);
    }

    public function setAccountInfoEmail(string $_UUID, string $_Email): string
    {
        // 檢查是否符合資料庫規格
        if (strlen($_Email) > 64)
            return "電子信箱長度不符合規定。";
        $emailLower = strtolower($_Email);
        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_EMAIL__ . "='$emailLower' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);
    }

    public function setAccountInfoAlias(string $_UUID, string $_Alias): string
    {
        // 檢查是否符合資料庫規格
        if (strlen($_Alias) > 16 || strlen($_Alias) < 4)
            return "暱稱長度不符合規定。";
        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_ALIAS__ . "='$_Alias' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);
    }

    public function setAccountInfoImage(string $_UUID, string $_ImageSrc): string
    {
        // 檢查是否符合資料庫規格
        if (strlen($_ImageSrc) > 64)
            return "圖片路徑長度不符合規定。";
        $updateAccountInfoCommand = "UPDATE AccountInfo SET " . __ACCOUNTINFO_IMAGE__ . "='$_ImageSrc' WHERE " . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($updateAccountInfoCommand);
    }
}
?>