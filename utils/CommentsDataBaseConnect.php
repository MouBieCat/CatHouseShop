<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE Comments(
 *   uUUID varchar(36) PRIMARY KEY NOT NULL, 
 *   cStars tinyint NOT NULL
 *   cMessage varchar(200) NOT NULL
 *   cTime varchar(20) NOT NULL
 * );
 * 
 * DESCRIBE Comments;
 * +----------+--------------+------+-----+---------+-------+
 * | Field    | Type         | Null | Key | Default | Extra |
 * +----------+--------------+------+-----+---------+-------+
 * | uUUID    | varchar(36)  | NO   | PRI | NULL    |       |
 * | cStars   | tinyint      | NO   |     | NULL    |       |
 * | cMessage | varchar(200) | NO   |     | NULL    |       |
 * | cTime    | varchar(20)  | NO   |     | NULL    |       |
 * +----------+--------------+------+-----+---------+-------+
 */
require_once("DataBaseConnection.php");

/**
 * 有關操作帳戶評論的資料庫類
 */
final class CommentsDataBaseConnect extends DataBaseConnect
{
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 該帳戶是否已經評論過了
     * @param string $_UUID 標識碼
     * @return bool
     */
    public function isCommentOfAccount(string $_UUID): bool
    {
        $selectCommentForAccountCommand = "SELECT * FROM Comments WHERE uUUID='$_UUID';";
        $selectCommentForAccountResult = $this->m_ConnectObject->query($selectCommentForAccountCommand);
        return ($selectCommentForAccountResult->num_rows !== 0);
    }

    /**
     * 隨機獲取評論
     * @param int  $_Stars  評論星星數
     * @param int  $_Counts 抓取數量
     * @return mysqli_result
     */
    public function getRandComments(int $_Stars = 5, int $_Counts = 3): mysqli_result
    {
        $selectCommentsCommand = "SELECT * FROM Comments WHERE cStars=$_Stars ORDER BY rand() LIMIT $_Counts;";
        return $this->m_ConnectObject->query($selectCommentsCommand);
    }

    /**
     * 根據帳戶評論
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getCommentOfAccount(string $_UUID): mysqli_result
    {
        $selectCommentForAccountCommand = "SELECT * FROM Comments WHERE uUUID='$_UUID';";
        return $this->m_ConnectObject->query($selectCommentForAccountCommand);
    }

    /**
     * 添加一筆評論資料
     * @param string $_UUID    標識碼
     * @param int    $_Stars   星星數
     * @param string $_Message 評論內容
     * @return string
     */
    public function addComment(string $_UUID, int $_Stars, string $_Message): string
    {
        // 檢查數據是否符合資料庫規格
        if ($_Stars < 0 || $_Stars > 5)
            return "評論星星數請介於一至五之間。";
        if (strlen($_Message) > 200 || strlen($_Message) < 10)
            return "評論訊息過短或過長。";

        $insertCommentCommand = "INSERT INTO Comments(uUUID, cStars, cMessage, cTime) VALUES ('$_UUID', $_Stars, '$_Message', now())";
        $insertCommentResult = $this->m_ConnectObject->query($insertCommentCommand);
        // 判斷資料是否插入成功
        if ($insertCommentResult)
            return "完成！您的評論已經提交，感謝您寶貴的評論。";
        return "您已經評論完成。我們將定期清理數據庫，您可以在那時重新發表對我們的看法。";
    }
}
?>