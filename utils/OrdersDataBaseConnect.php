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
 * CREATE TABLE Orders(
 *  oUUID varchar(36) NOT NULL, 
 *  oProduct int NOT NULL, 
 *  oCount int NOT NULL DEFAULT 1
 * );
 * 
 * DESCRIBE Orders;
 * +----------+-------------+------+-----+---------+-------+
 * | Field    | Type        | Null | Key | Default | Extra |
 * +----------+-------------+------+-----+---------+-------+
 * | oUUID    | varchar(36) | NO   |     | NULL    |       |
 * | oProduct | int         | NO   |     | NULL    |       |
 * | oCount   | int         | NO   |     | 1       |       |
 * +----------+-------------+------+-----+---------+-------+
 */
require_once("DataBaseConnection.php");
require_once("ProductsDataBaseConnect.php");

define("__ORDERS_UUID__", "oUUID");
define("__ORDERS_PRODUCT__", "oProduct");
define("__ORDERS_COUNT__", "oCount");

final class OrdersDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 獲取所有訂單資料
     * @return mysqli_result
     */
    public function getOrders(): mysqli_result
    {
        $selectOrdersCommand = "SELECT * FROM Orders;";
        return $this->m_ConnectObject->query($selectOrdersCommand);
    }

    /**
     * 根據標識碼獲取訂單資料
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getOrdersByUUID(string $_UUID): mysqli_result
    {
        $selectOrderByUUIDCommand = "SELECT * FROM Orders WHERE " . __ORDERS_UUID__ . "='$_UUID';";
        return $this->m_ConnectObject->query($selectOrderByUUIDCommand);
    }

    /**
     * 根據商品代碼獲取訂單資料
     * @param int $_Product 商品
     * @return mysqli_result
     */
    public function getOrdersByProduct(int $_Product): mysqli_result
    {
        $selectOrderByProductCommand = "SELECT * FROM Orders WHERE " . __ORDERS_PRODUCT__ . "=$_Product;";
        return $this->m_ConnectObject->query($selectOrderByProductCommand);
    }

    /**
     * 根據商品代碼獲取訂單資料
     * @param string $_UUID    標識碼
     * @param int    $_Product 商品
     * @return mysqli_result
     */
    public function getOrder(string $_UUID, int $_Product): mysqli_result
    {
        $selectOrder = "SELECT * FROM Orders WHERE " . __ORDERS_UUID__ . "='$_UUID' AND " . __ORDERS_PRODUCT__ . "=$_Product;";
        return $this->m_ConnectObject->query($selectOrder);
    }

    /**
     * 添加一筆訂單資料
     * @param string $_UUID    標識碼
     * @param int    $_Product 商品
     * @param int    $_Count   數量
     * @return void
     */
    public function addOrder(string $_UUID, int $_Product, int $_Count): void
    {
        if ($this->updateOrder($_UUID, $_Product, $_Count))
            return;

        $insertOrderCommand = "INSERT INTO Orders(" . __ORDERS_UUID__ . ", " . __ORDERS_PRODUCT__ . ", " . __ORDERS_COUNT__ . ") VALUES ('$_UUID', $_Product, $_Count);";
        $this->m_ConnectObject->query($insertOrderCommand);
    }

    /**
     * 更新訂單資料
     * @param string $_UUID 標識碼
     * @param int $_Product 商品
     * @param int $_Count   數量
     * @return bool
     */
    private function updateOrder(string $_UUID, int $_Product, int $_Count): bool
    {
        $selectResult = $this->getOrder($_UUID, $_Product);
        if ($selectResult->num_rows === 0)
            return false;

        $selectRow = $selectResult->fetch_assoc();
        $newCount = $selectRow[__ORDERS_COUNT__] + $_Count;
        $updateCommand = "UPDATE Orders SET " . __ORDERS_COUNT__ . "=$newCount WHERE " . __ORDERS_UUID__ . "='$_UUID' AND " . __ORDERS_PRODUCT__ . "=$_Product;";
        $this->m_ConnectObject->query($updateCommand);
        return true;
    }

    /**
     * 刪除訂單資料
     * @param string $_UUID 標識碼
     * @param int $_Product 商品
     * @return void
     */
    public function removeOrder(string $_UUID, int $_Product): void
    {
        $deleteOrderCommand = "DELETE FROM Orders WHERE " . __ORDERS_UUID__ . "='$_UUID' AND " . __ORDERS_PRODUCT__ . "=$_Product;";
        $this->m_ConnectObject->query($deleteOrderCommand);
    }

    /**
     * 清除訂單資料
     * @param string $_UUID 標識碼
     * @return void
     */
    public function clearOrders(string $_UUID): void
    {
        $deleteOrderCommand = "DELETE FROM Orders WHERE " . __ORDERS_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($deleteOrderCommand);
    }

    /**
     * 清除無效的商品訂單
     * @param string $_UUID 標識碼
     * @return void
     */
    public function clearInvalidOrders(string $_UUID): void
    {
        $selectResult = $this->getOrdersByUUID($_UUID);
        if ($selectResult === 0)
            return;

        $productConnect = new ProductsDataBaseConnect();

        while ($orderRow = $selectResult->fetch_assoc()) {
            $productResult = $productConnect->getProduct($orderRow["oProduct"]);
            if ($productResult->num_rows === 0)
                $this->removeOrder($_UUID, $orderRow["oProduct"]);
        }
    }
}
?>