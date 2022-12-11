<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE Product (
 *  pID INT NOT NULL PRIMARY KEY, 
 *  pTitle varchar(16) NOT NULL, 
 *  pPrice DECIMAL(10,0) NOT NULL, 
 *  pImageSrc varchar(64) NOT NULL, 
 *  pCount INT NOT NULL DEFAULT 0,
 *  pEvent TINYINT NOT NULL DEFAULT 0
 * );
 * 
 * DESCRIBE Product;
 * +-----------+---------------+------+-----+---------+-------+
 * | Field     | Type          | Null | Key | Default | Extra |
 * +-----------+---------------+------+-----+---------+-------+
 * | pID       | int           | NO   | PRI | NULL    |       | -> SELECT RAND()*(99999999-10000000)+10000000;
 * | pTitle    | varchar(16)   | NO   |     | NULL    |       |
 * | pPrice    | decimal(10,0) | NO   |     | NULL    |       |
 * | pImageSrc | varchar(64)   | NO   |     | NULL    |       |
 * | pCount    | int           | NO   |     | 0       |       |
 * | pEvent    | tinyint       | NO   |     | 0       |       |
 * +-----------+---------------+------+-----+---------+-------+
 */
require_once("DataBaseConnection.php");

define("__PRODUCT_ID__", "pID");
define("__PRODUCT_TITLE__", "pTitle");
define("__PRODUCT_PRICE__", "pPrice");
define("__PRODUCT_IMAGE__", "pImageSrc");
define("__PRODUCT_COUNT__", "pCount");

final class ProductsDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 獲取所有商品資料
     * @param  string $_Search 搜索
     * @return mysqli_result
     */
    public function getProducts(string $_Search = NULL): mysqli_result
    {
        if ($_Search !== NULL)
            return $this->getSearchProducts($_Search);
        $selectProductsCommand = "SELECT * FROM Product;";
        return $this->m_ConnectObject->query($selectProductsCommand);
    }

    /**
     * 獲取指定商品資料
     * @param int $_ID 商品ID
     * @return mysqli_result
     */
    public function getProduct(int $_ID): mysqli_result
    {
        $selectProductCommand = "SELECT * FROM Product WHERE " . __PRODUCT_ID__ . "=$_ID;";
        return $this->m_ConnectObject->query($selectProductCommand);
    }

    /**
     * 根據頁數獲取指定數量的商品資料
     * @param int     $_Page  頁數
     * @param int     $_Count 數量
     * @param  string $_Search 搜索
     * @return mysqli_result
     */
    public function getProductsOfPage(int $_Page = 1, int $_Count = 5, string $_Search = NULL): mysqli_result
    {
        if ($_Page < 1)
            $_Page = 1;

        if ($_Search !== NULL)
            return $this->getSearchProductsOfPage($_Page, $_Count, $_Search);
        $startProductIndex = ($_Page - 1) * $_Count;
        $selectProductOfPageCommand = "SELECT * FROM Product LIMIT $startProductIndex, $_Count;";
        return $this->m_ConnectObject->query($selectProductOfPageCommand);
    }

    /**
     * 獲取相關標題的商品資料
     * @param string $_Title 標題
     * @return mysqli_result
     */
    public function getSearchProducts(string $_Title): mysqli_result
    {
        $selectSearchProductsCommand = "SELECT * FROM Product WHERE " . __PRODUCT_TITLE__ . " LIKE '%$_Title%';";
        return $this->m_ConnectObject->query($selectSearchProductsCommand);
    }

    /**
     * 獲取相關標題頁數的商品資料
     * @param int    $_Page  頁數
     * @param int    $_Count 數量
     * @param string $_Title 標題
     * @return mysqli_result
     */
    public function getSearchProductsOfPage(int $_Page = 1, int $_Count = 5, string $_Title): mysqli_result
    {
        if ($_Page < 1)
            $_Page = 1;
        $startProductIndex = ($_Page - 1) * $_Count;
        $selectSearchProductsCommand = "SELECT * FROM Product WHERE " . __PRODUCT_TITLE__ . " LIKE '%$_Title%' LIMIT $startProductIndex, $_Count;";
        print($selectSearchProductsCommand);
        return $this->m_ConnectObject->query($selectSearchProductsCommand);
    }
}
?>