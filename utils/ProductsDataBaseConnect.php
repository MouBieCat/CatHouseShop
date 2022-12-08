<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE Products(
 *   pID INT NOT NULL PRIMARY KEY, 
 *   pTitle varchar(32) NOT NULL, 
 *   pPrice DECIMAL(10,2) NOT NULL, 
 *   pImageSrc varchar(256) NOT NULL
 * );
 * 
 * DESCRIBE Products;
 * +--------------+---------------+------+-----+---------+-------+
 * | Field        | Type          | Null | Key | Default | Extra |
 * +--------------+---------------+------+-----+---------+-------+
 * | pID          | int           | NO   | PRI | NULL    |       |
 * | pTitle       | varchar(32)   | NO   |     | NULL    |       |
 * | pPrice       | decimal(10,2) | NO   |     | NULL    |       |
 * | pImageSrc    | varchar(256)  | NO   |     | NULL    |       |
 * +--------------+---------------+------+-----+---------+-------+
 */
require_once("AccountsDataBaseConnect.php");

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
     * @return mysqli_result
     */
    public function getProducts(): mysqli_result
    {
        $selectProductsCommand = "SELECT * FROM Products;";
        return $this->m_ConnectObject->query($selectProductsCommand);
    }

    /**
     * 根據頁數獲取指定數量的資料
     * @param int $_Page  頁數
     * @param int $_Count 數量
     * @return mysqli_result
     */
    public function getProductsOfPage(int $_Page = 1, int $_Count = 5): mysqli_result
    {
        $startIndex = ($_Page - 1) * $_Count;
        $selectProductOfPageCommand = "SELECT * FROM Products LIMIT $startIndex, $_Count;";
        return $this->m_ConnectObject->query($selectProductOfPageCommand);
    }

    /**
     * 獲取指定ID的商品資料
     * @param int $_ID      商品ID
     * @return mysqli_result
     */
    public function getProduct(int $_ID): mysqli_result
    {
        $selectProductCommand = "SELECT * FROM Products WHERE pID=$_ID;";
        return $this->m_ConnectObject->query($selectProductCommand);
    }
}
?>