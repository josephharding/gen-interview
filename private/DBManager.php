<?php

class DBManager {

    private $m_dbHandle;

    public function __construct() {
        try {
            $this->m_dbHandle = new PDO("mysql:host=localhost;port=3306;dbname=high_scores", "hs_admin", "kixeye_interview");
            $this->m_dbHandle->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch(Exception $e) {
            arr_log($e);
        }
    }

    public function addScoreEntry($userId, $userScore) {
        $statement = $this->m_dbHandle->prepare("INSERT INTO scores (user_id, score) VALUE (:user_id, :user_score)");
        $statement->execute(array('user_id' => $userId, 'user_score' => $userScore));
    }

    public function getUserHighScore($userId) {
        $result = -1;
        $statement = $this->m_dbHandle->prepare("SELECT high_score FROM users WHERE user_id = :user_id");
        if ($statement->execute(array('user_id' => $userId))) {
            while($row = $statement->fetch()) {
                $result = $row['high_score'];
            }
        }
        return $result;
    }

    public function setUserHighScore($userId, $userScore) {
        $statement = $this->m_dbHandle->prepare("UPDATE users SET high_score = :new_high_score WHERE user_id = :user_id");
        $statement->execute(array('user_id' => $userId, 'new_high_score' => $userScore)); 
    }

    public function getTopUsersByScore($numUsers) {
        $result = array();
        $statement = $this->m_dbHandle->prepare("SELECT * from users ORDER BY high_score DESC LIMIT :limit");
        # have to bind here using different pattern in order to cast limit to an int
        $statement->bindValue('limit', $numUsers, PDO::PARAM_INT);
        if($statement->execute()) {
            while($row = $statement->fetch()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    /*
    Close the connection when we are finished
    */
    public function close() {
        $this->m_dbHandle = null;
    }

}
