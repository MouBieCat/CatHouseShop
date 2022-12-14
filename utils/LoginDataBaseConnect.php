<?php
require_once("AccountsDataBaseConnect.php");

/**
 * 用於登入處理的資料庫類
 */
final class LoginDataBaseConnect extends AccountsDataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 嘗試登入一個帳戶
     * @param string $_Name   登入名稱
     * @param string $_Passwd 登入密碼
     * @return array
     */
    public function tryLogin(string $_Name, string $_Passwd): array
    {
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查是否為有效數據
        if (empty($_Name) || empty($_Passwd)) {
            $returnArray[__RETURN_CONTENT__] = "帳戶名稱或是密碼欄位不可為空。";
            return $returnArray;
        }

        // 從資料庫取出相應資料
        $selectAccountResult = $this->getAccount($_Name);
        if ($selectAccountResult->num_rows === 0) {
            $returnArray[__RETURN_CONTENT__] = "帳戶名稱還沒有被註冊，請問是我們的新朋友嗎？";
            return $returnArray;
        }

        // 處理資料並核對資料是否正確
        $selectAccountRow = $selectAccountResult->fetch_assoc();
        if ($_Name === $selectAccountRow[__ACCOUNT_NAME__] && $_Passwd === $selectAccountRow[__ACCOUNT_PASSWD__]) {
            $returnArray[__RETURN_RESULT__] = TRUE;
            $returnArray[__RETURN_CONTENT__] = $selectAccountRow[__ACCOUNT_UUID__];
            return $returnArray;
        }

        // 如果資料核對有錯誤
        $returnArray[__RETURN_CONTENT__] = "帳戶名稱或密碼輸入錯誤。";
        return $returnArray;
    }
}
?>