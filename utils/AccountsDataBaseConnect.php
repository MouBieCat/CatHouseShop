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
require_once("DataBaseConnection.php");

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
     * 添加一比帳戶
     * @param string $_Name     帳戶名稱
     * @param string $_Password 帳戶密碼
     * @return mysqli_result|bool
     */
    protected function addAccount(string $_Name, string $_Password)
    {
        $insertAccountCommand = "INSERT INTO Accounts(uName, uPasswd, uUUID, uRegistrationTime) VALUES ('$_Name', '$_Password', uuid(), now());";
        return $this->m_ConnectObject->query($insertAccountCommand);
    }

    /**
     * 獲取所有帳戶資料
     * @return mysqli_result
     */
    public function getAccounts(): mysqli_result
    {
        $selectAccountsCommand = "SELECT * FROM Accounts;";
        return $this->m_ConnectObject->query($selectAccountsCommand);
    }

    /**
     * 獲取指定帳戶資料
     * @param string $_Name 帳戶名稱
     * @return mysqli_result
     */
    public function getAccount(string $_Name): mysqli_result
    {
        $selectAccountCommand = "SELECT * FROM Accounts WHERE uName='$_Name';";
        return $this->m_ConnectObject->query($selectAccountCommand);
    }

    /**
     * 根據該標識碼獲取帳戶
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getAccountOfUUID(string $_UUID): mysqli_result
    {
        $selectAccountOfUUIDCommand = "SELECT * FROM Accounts WHERE uUUID='$_UUID';";
        return $this->m_ConnectObject->query($selectAccountOfUUIDCommand);
    }

    /**
     * 該標識碼是否已經被帳戶生成
     * @param string $_UUID 標識碼
     * @return bool
     */
    public function isGeneratedUUID(string $_UUID): bool
    {
        $selectUUIDResult = $this->getAccountOfUUID($_UUID);
        return ($selectUUIDResult->num_rows !== 0);
    }
}
?>