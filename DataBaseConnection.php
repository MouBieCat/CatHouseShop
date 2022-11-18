<?php
interface Connect {
    /**
     * 連接
     * @return 使否成功
     */
    public function connect() : bool;

    /**
     * 斷開
     * @return 無
     */
    public function disconnect() : void;
}

class MySQLConnect implements Connect {
    // 資料庫位址
    private $m_Address;

    // 資料庫名稱
    private $m_DatabaseName;

    // 登入使用者名稱
    private $m_UserName;

    // 登入使用者密碼
    private $m_UserPassword;

    // 連接物件
    protected $m_ConnectObject;

    /**
     * 建構子
     * @param $_DatabaseName 資料庫名稱
     * @param $_Address      資料庫位址
     * @param $_User         登入使用者名稱
     * @param $_Password     登入使用者密碼
     */
    public function __construct(string $_DatabaseName = "CatHouseShopDB", string $_Address = "localhost", string $_User = "root", string $_Password = "HongYi") {
        $this->m_Address      = $_Address;
        $this->m_UserName     = $_User;
        $this->m_UserPassword = $_Password;
        $this->m_DatabaseName = $_DatabaseName;
        $this->connect();
    }

    /**
     * 連接
     * @return 使否成功
     */
    public function connect() : bool {
        // 連接資料庫
        $this->m_ConnectObject = @mysqli_connect($this->m_Address, $this->m_UserName, $this->m_UserPassword);

        // 如果連接成功
        if ($this->m_ConnectObject) {
            @mysqli_query($this->m_ConnectObject, "SET NAMES 'UTF8';");
            @mysqli_select_db($this->m_ConnectObject, $this->m_DatabaseName);
            return true;
        }
        die("連接後台資料庫失敗。");
        return false;
    }

    /**
     * 斷開
     * @return 無
     */
    public function disconnect() : void {
        if ($this->m_ConnectObject) {
            mysqli_close($this->m_ConnectObject);
        }
    }

    /**
     * 析構函數
     */
    public function __destruct() {
        $this->disconnect();
    }
}
?>