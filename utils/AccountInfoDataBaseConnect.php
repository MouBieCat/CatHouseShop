<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE AccountInfo(
 *  iUUID varchar(36) PRIMARY KEY NOT NULL, 
 *  iPhone varchar(15) NULL, 
 *  iEmail varchar(64) NULL, iAlias varchar(16) NOT NULL DEFAULT "Member", 
 *  iImageSrc varchar(64) NOT NULL DEFAULT "./resource/DefaultAccountInfoImage.jpg"
 * );
 * 
 * DESCRIBE AccountInfo;
 * +-----------+-------------+------+-----+----------------------------------------+-------+
 * | Field     | Type        | Null | Key | Default                                | Extra |
 * +-----------+-------------+------+-----+----------------------------------------+-------+
 * | iUUID     | varchar(36) | NO   | PRI | NULL                                   |       |
 * | iPhone    | varchar(15) | YES  |     | NULL                                   |       |
 * | iEmail    | varchar(64) | YES  |     | NULL                                   |       |
 * | iAlias    | varchar(16) | NO   |     | Member                                 |       |
 * | iImageSrc | varchar(64) | NO   |     | ./resource/DefaultAccountInfoImage.jpg |       |
 * +-----------+-------------+------+-----+----------------------------------------+-------+
 */
require_once("AccountsDataBaseConnect.php");

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
     * @param string $_UUID
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
        $deleteAccountInfoCommand = "DELETE FROM AccountInfo (" . __ACCOUNTINFO_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($deleteAccountInfoCommand);
    }
}
?>