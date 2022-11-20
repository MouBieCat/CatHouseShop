<?php
include "DataBaseConnection.php";

$obj = new SQLConnect();


// final class ProductConnection extends MySQLConnect {
//     private $m_ColumnSize = 0;
//     private $m_Column     = "*";

//     private $m_FiltrSize  = 0;
//     private $m_Filtr      = "";

//     /**
//     * 建構子
//     */
//     public function __construct() {
//         parent::__construct();
//     }

//     /**
//      * 添加查找的欄位 (預設 * )
//      * @param  $_Name 欄位名稱
//      * @return 當前對象
//      */
//     public function column(string $_Name) : ProductConnection {
//         if ($this->m_ColumnSize == 0) {
//             $this->m_Column = "";
//             $this->m_Column = $this->m_Column.$_Name;
//         } else $this->m_Column = $this->m_Column.", ".$_Name;
//         $this->m_ColumnSize = $this->m_ColumnSize + 1;
//         return $this;
//     }

//     /**
//      * 過濾條件
//      * @param  $_Key   欄位名
//      * @param  $_Vaule 欄位值
//      * @return 當前對象
//      */
//     public function filtr(string $_Key, string $_Vaule) : ProductConnection {
//         if ($this->m_FiltrSize == 0) $this->m_Filtr = $this->m_Filtr."WHERE ";
//         $this->m_Filtr = $this->m_Filtr."$_Key='$_Vaule'";
//         $this->m_FiltrSize = $this->m_FiltrSize + 1;
//         return $this;
//     }

//     /**
//      * 根據上述結果，獲取所有相關製品
//      * @return 資料庫查詢結果
//      */
//     public function getProducts() {
//         $selectProductCommand = "SELECT $this->m_Column FROM Product $this->m_Filtr";   // 最終指令
//         print($selectProductCommand);
//         return mysqli_query($this->m_ConnectObject, $selectProductCommand);                 // 返回結果
//     }
// }

// $oldPath=$_FILES['image']['tmp_name'];
// move_uploaded_file($oldPath,"img/".$_FILES['image']['name']);
?>

<form action="test.php" enctype="multipart/form-data" method="post">
    圖片1上傳：<input type="file" name="image">
    <!--圖片2上傳：<input type="file" name="image2">-->
    <button type="submit">上傳</button>
</form>