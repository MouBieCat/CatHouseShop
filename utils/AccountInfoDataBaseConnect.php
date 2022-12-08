<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE AccountInfo(
 *   uUUID varchar(36) PRIMARY KEY NOT NULL, 
 *   uEmail varchar(64), 
 *   uPhone varchar(15), 
 *   uAlias varchar(16) NOT NULL DEFAULT "會員帳戶",
 *   uImageSrc varchar(32) NOT NULL DEFAULT "./Resource/DefaultUserImage.jpg"
 * );
 * 
 * DESCRIBE AccountInfo;
 * +-----------+-------------+------+-----+---------------------------------+-------+
 * | Field     | Type        | Null | Key | Default                         | Extra |
 * +-----------+-------------+------+-----+---------------------------------+-------+
 * | uUUID     | varchar(36) | NO   | PRI | NULL                            |       |
 * | uEmail    | varchar(64) | YES  |     | NULL                            |       |
 * | uPhone    | varchar(15) | YES  |     | NULL                            |       |
 * | uAlias    | varchar(16) | NO   |     | 會員帳戶                         |       |
 * | uImageSrc | varchar(32) | NO   |     | ./Resource/DefaultUserImage.jpg |       |
 * +-----------+-------------+------+-----+---------------------------------+-------+
 */
require_once("AccountsDataBaseConnect.php");

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
     * 獲取帳戶資訊
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getAccountInfo(string $_UUID): mysqli_result
    {
        $selectAccountInfoCommand = "SELECT * FROM AccountInfo WHERE uUUID='$_UUID';";
        return $this->m_ConnectObject->query($selectAccountInfoCommand);
    }

    /**
     * 添加一筆預設帳戶資訊
     * @param string $_UUID      標識碼
     * @param bool   $_CheckUUID 是否檢查標識碼
     * @return void
     */
    public function addDefaultAccountInfo(string $_UUID, bool $_CheckUUID = TRUE): void
    {
        if ($_CheckUUID === TRUE) {
            $accountConnect = new AccountsDataBaseConnect();
            // 判斷該UUID是否已經被帳戶生成
            if (!$accountConnect->isGeneratedUUID($_UUID))
                return;
        }

        $insertAccountInfoCommand = "INSERT INTO AccountInfo(uUUID) VALUES ('$_UUID');";
        $this->m_ConnectObject->query($insertAccountInfoCommand);
    }
}
?>