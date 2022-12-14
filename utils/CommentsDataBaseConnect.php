<?php
/**
 * 相關數據表結構：
 * 
 * CREATE TABLE Comment (
 *  cUUID varchar(36) PRIMARY KEY NOT NULL, 
 *  cStars TINYINT NOT NULL, 
 *  cMessage varchar(256) NOT NULL, 
 *  cTime varchar(20) NOT NULL
 * );
 * 
 * DESCRIBE Comment;
 * +----------+--------------+------+-----+---------+-------+
 * | Field    | Type         | Null | Key | Default | Extra |
 * +----------+--------------+------+-----+---------+-------+
 * | cUUID    | varchar(36)  | NO   | PRI | NULL    |       |
 * | cStars   | tinyint      | NO   |     | NULL    |       |
 * | cMessage | varchar(256) | NO   |     | NULL    |       |
 * | cTime    | varchar(20)  | NO   |     | NULL    |       |
 * +----------+--------------+------+-----+---------+-------+
 */
require_once("DataBaseConnection.php");

define("__COMMENT_UUID__", "cUUID");
define("__COMMENT_STARS__", "cStars");
define("__COMMENT_MESSAGE__", "cMessage");
define("__COMMENT_TIME__", "cTime");

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
     * 獲取所有評論資料
     * @return mysqli_result
     */
    public function getComments(): mysqli_result
    {
        $selectCommentsCommand = "SELECT * FROM Comment;";
        return $this->m_ConnectObject->query($selectCommentsCommand);
    }

    /**
     * 獲取一筆特定評論資料
     * @param string $_UUID 標識碼
     * @return mysqli_result
     */
    public function getComment(string $_UUID): mysqli_result
    {
        $selectCommentCommand = "SELECT * FROM Comment WHERE " . __COMMENT_UUID__ . "='$_UUID';";
        return $this->m_ConnectObject->query($selectCommentCommand);
    }

    /**
     * 隨機獲取特定數量評論資料
     * @return mysqli_result
     */
    public function getRnadComments(int $_Stars = 5, int $_Count = 3): mysqli_result
    {
        $randSelectCommentsCommand = "SELECT * FROM Comment WHERE cStars=$_Stars ORDER BY rand() LIMIT $_Count;";
        return $this->m_ConnectObject->query($randSelectCommentsCommand);
    }

    /**
     * 檢查該帳戶是否已經評論
     * @param string $_UUID 標識碼
     * @return bool
     */
    public function isComment(string $_UUID): bool
    {
        $selectCommentResult = $this->getComment($_UUID);
        return ($selectCommentResult->num_rows !== 0);
    }

    /**
     * 添加一筆評論資料
     * @param string $_UUID    標識碼 
     * @param int $_Stars      星星數
     * @param string $_Message 內容
     * @return string
     */
    public function addComment(string $_UUID, int $_Stars, string $_Message): array
    {
        // 返回結果
        $returnArray[__RETURN_RESULT__] = FALSE;
        $returnArray[__RETURN_CONTENT__] = NULL;

        // 檢查資料是否符合資料庫規格
        if ($_Stars < 0 || $_Stars > 5) {
            $returnArray[__RETURN_CONTENT__] = "評論星星數不符合規格。";
            return $returnArray;
        }

        if (strlen($_Message) > 256 || strlen($_Message) < 8) {
            $returnArray[__RETURN_CONTENT__] = "評論內容不符合規格。";
            return $returnArray;
        }

        $insertCommentCommand = "INSERT INTO Comment (" . __COMMENT_UUID__ . ", " . __COMMENT_STARS__ . ", " . __COMMENT_MESSAGE__ . ", " . __COMMENT_TIME__ . ") VALUES ('$_UUID', $_Stars, '$_Message', now());";
        $insertCommentResult = $this->m_ConnectObject->query($insertCommentCommand);

        if ($insertCommentResult) {
            $returnArray[__RETURN_RESULT__] = TRUE;
            $returnArray[__RETURN_CONTENT__] = "您的評論提交成功，感謝來自您寶貴的評論。";
            return $returnArray;
        }

        $returnArray[__RETURN_CONTENT__] = "您已經評論完成。我們將定期清理數據庫，您可以在那時重新發表對我們的看法。";
        return $returnArray;
    }

    /**
     * 刪除一筆指定的評論資料
     * @param string $_UUID 標識碼
     * @return void
     */
    public function removeComment(string $_UUID): void
    {
        $removeCommentCommand = "DELETE FROM Comment WHERE " . __COMMENT_UUID__ . "='$_UUID';";
        $this->m_ConnectObject->query($removeCommentCommand);
    }

    /**
     * 清除所有評論
     * @return void
     */
    public function clearComments(): void
    {
        $deleteCommentsCommand = "DELETE FROM Comment;";
        $this->m_ConnectObject->query($deleteCommentsCommand);
    }
}
?>