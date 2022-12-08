<?php
/**
 * 表示一個連接接口類
 */
interface IConnect
{
    /**
     * 連接
     * @return bool
     */
    public function connect(): bool;

    /**
     * 斷開連接
     * @return void
     */
    public function disconnect(): void;
}

/**
 * 代表一個數據庫連接類
 */
class DataBaseConnect implements IConnect
{
    private $m_Address;

    private $m_UserName;

    private $m_UserPassword;

    private $m_DatabaseName;

    protected $m_ConnectObject;

    /**
     * 建構子
     * @param string $_Address      資料庫位址
     * @param string $_User         登入帳戶
     * @param string $_Password     登入密碼
     * @param string $_DatabaseName 資料庫名稱
     */
    public function __construct(
        string $_Address = "localhost",
        string $_User = "root",
        string $_Password = "MouBieCat",
        string $_DatabaseName = "CatHouseDB"
    )
    {
        $this->m_Address = $_Address;
        $this->m_UserName = $_User;
        $this->m_UserPassword = $_Password;
        $this->m_DatabaseName = $_DatabaseName;
        $this->connect(); // 連接
    }

    /**
     * 連接
     * @return bool
     */
    public function connect(): bool
    {
        // 連接資料庫
        $this->m_ConnectObject = new mysqli(
            $this->m_Address, // 資料庫位址
            $this->m_UserName, // 使用者名稱
            $this->m_UserPassword, // 使用者密碼
            $this->m_DatabaseName // 資料庫名稱

        );

        // 是否連接成功
        if ($this->m_ConnectObject->connect_error)
            return FALSE; // 連接失敗

        // 連接成功
        $this->m_ConnectObject->query("SET NAMES 'UTF8';");
        return TRUE;
    }

    /**
     * 斷開連接
     * @return void
     */
    public function disconnect(): void
    {
        // 斷開連接
        $this->m_ConnectObject->close();
    }

    /**
     * 解構子
     */
    public function __destruct()
    {
        $this->disconnect(); // 斷開
    }
}
?>