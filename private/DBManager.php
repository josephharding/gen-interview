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
            $this->m_dbHandle = new PDO("mysql:host=localhost;port=3306;dbname=high_scores", "hs_admin", "kixeye_interview");
            // errors left on here for ease of debugging
            $this->m_dbHandle->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch(Exception $e) {
            Util::arr_log($e);
        }
    }
    
    /**
     * Gets all the user ids stored in the users table
     *
     * @return array    an array of user ids
     */
    public function getAllUserIds() {
        $result = array();
        $statement = $this->m_dbHandle->prepare("SELECT user_id FROM users");
        if($statement->execute()) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result[] = $row[0];
            }
        }
        return $result;
    }
    
    /**
     * Gets the number of users in the users table
     *
     * @return int  the number of users in the users table
     */
    public function getNumRowsInUsers() {
        $result = null;
        $statement = $this->m_dbHandle->prepare("SELECT COUNT(*) FROM users");
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
     * Insert a new user row
     * 
     * @param string $userId    the unique user id
     */   
    public function setUserEntry($userId) {
        $statement = $this->m_dbHandle->prepare("INSERT INTO users (user_id, high_score) VALUE (:user_id, :high_score)");
        $statement->execute(array('user_id' => $userId, 'high_score' => 0));       
    }

    /**
     * Gets the entire user row given the user id
     *
     * @return array the user's row formatted as an array
     */
    public function getUserEntry($userId) {
        $result = null;
        $statement = $this->m_dbHandle->prepare("SELECT * FROM users WHERE user_id = :user_id");
        if($statement->execute(array('user_id' => $userId))) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result = $row[0];
            }
        }
        Util::arr_log($result);
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
        $statement->execute(array('user_id' => $userId, 'user_score' => $userScore));
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
        $statement = $this->m_dbHandle->prepare("SELECT high_score FROM users WHERE user_id = :user_id");
        if ($statement->execute(array('user_id' => $userId))) {
            while($row = $statement->fetch(PDO::FETCH_NUM)) {
                $result = $row[0];
            }
        }
        return $result;
    }
    
    /**
     * Sets a user's high score
     *
     * @param string $userID        the user's unique id
     * @param int    $userScore     the high score for the user
     */
    public function setUserHighScore($userId, $userScore) {
        $statement = $this->m_dbHandle->prepare("UPDATE users SET high_score = :new_high_score WHERE user_id = :user_id");
        $statement->execute(array('user_id' => $userId, 'new_high_score' => $userScore)); 
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
        $statement = $this->m_dbHandle->prepare("SELECT user_id from users ORDER BY high_score DESC LIMIT :limit");
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
