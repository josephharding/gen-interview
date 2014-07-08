<?php

/*

DBManager.php

Responsible for setting and getting data from persistence

Currently implemented with PDO and the mysql PDO module

*/

class DBManager {

    private $m_dbHandle;

    public function __construct() {
        try {
            $this->m_dbHandle = new PDO("mysql:host=localhost;port=3306;dbname=jh_scores", "hs_admin", "kixeye_interview");
            // errors left on here for ease of debugging
            $this->m_dbHandle->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch(Exception $e) {
            Util::arr_log($e);
        }
    }
    
    /**
     * Gets all the unique user ids stored in the scores table
     *
     * @return array    an array of user ids
     */
    public function getAllUserIds() {
        $result = array();
        $statement = $this->m_dbHandle->prepare("SELECT user_id FROM scores GROUP BY user_id");
        if($statement->execute()) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result[] = $row[0];
            }
        }
        return $result;
    }
    
    /**
     * Gets the number of unique users in the scores table
     *
     * @return int  the number of users in the users table
     */
    public function getNumRowsInUsers() {
        $result = null;
        $statement = $this->m_dbHandle->prepare("SELECT COUNT(*) FROM (SELECT user_id FROM scores GROUP BY user_id) as T");
        if($statement->execute()) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result = $row[0];
            }
        }
        return $result;      
    }

    /**
     * Gets the total number of user ids who had score entries inserted during the specified time range
     *
     * @param string $start a date formatted like this: "2014/07/06 00:00:00" 
     * @param string $end   a date formatted like this: "2014/07/06 00:00:00"
     *
     * @return int the number of users who submitted scores in the range provided
     */
    public function getNumUniqueUsersInRange($start, $end) {
        $result = null;
        $statement = $this->m_dbHandle->prepare("SELECT COUNT(*) FROM (SELECT COUNT(*) FROM scores WHERE ts_create >= :start AND ts_create < :end GROUP BY user_id) as T");
        if($statement->execute(array('start' => $start, 'end' => $end))) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result = $row[0];
            }
        }
        return $result;       
    }

    /**
     * Inserts a new score row into the scores table
     *
     * @param string $userId        the unqiue user id
     * @param int    $userScore     the user's score
     */
    public function addScoreEntry($userId, $userScore) {
        $statement = $this->m_dbHandle->prepare("INSERT INTO scores (user_id, score) VALUE (:user_id, :user_score)");
        return $statement->execute(array('user_id' => $userId, 'user_score' => $userScore));
    }
    
    /**
     * Gets the user's high score
     *
     * @param string $userId    unqiue user id
     *
     * @return int              the user's high score
     */
    public function getUserHighScore($userId) {
        $result = 0;
        $statement = $this->m_dbHandle->prepare("SELECT MAX(score) FROM scores where user_id = :user_id");
        if ($statement->execute(array('user_id' => $userId))) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result = $row[0];
            }
        }
        return $result;
    }
    
    /**
     * Gets the user ids with the top X scores in order from greatest score to least
     *
     * @param int $numUsers the number of users to return in the result
     *
     * @return array        an ordered array of user ids
     */
    public function getTopUserIdsByScore($numUsers) {
        $result = array();
        $statement = $this->m_dbHandle->prepare("SELECT t1.user_id FROM scores t1 INNER JOIN( SELECT user_id, MAX(score) score FROM scores GROUP BY user_id ) t2 ON t1.user_id = t2.user_id AND t1.score = t2.score ORDER BY t2.score DESC LIMIT :limit");
        # have to bind here using different pattern in order to cast limit to an int
        $statement->bindValue('limit', $numUsers, PDO::PARAM_INT);
        if($statement->execute()) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result[] = $row[0];
            }
        }
        return $result;
    }

}
