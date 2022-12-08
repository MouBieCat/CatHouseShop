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
require_once("DataBaseConnection.php");

define("__ACCOUNT_NAME__", "uName");
define("__ACCOUNT_PASSWD__", "uPasswd");
define("__ACCOUNT_UUID__", "uUUID");
define("__ACCOUNT_TIME__", "uTime");

/**
 * 有關帳戶表資料庫連接類
 */
class AccountsDataBaseConnect extends DataBaseConnect
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
     * @return mysqli_result|bool
     */
    protected function addAccount(string $_Name, string $_Password)
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
}
?>