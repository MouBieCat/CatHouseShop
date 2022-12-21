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

require_once("AccountDataBaseConnect.php");

/**
 * 用於登錄處理的資料庫類
 */
final class LoginDataBaseConnect extends AccountDataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 嘗試登錄一個帳戶
     * @param string $_Name   登錄名稱
     * @param string $_Passwd 登錄密碼
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