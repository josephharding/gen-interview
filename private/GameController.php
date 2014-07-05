<?php

class GameController {

    public function recordScore($userId, $userScore) {
        // this could be placed in a CacheManager class
        
        $database = new DBManager();
        // add an entry to our score table
        $database->addScoreEntry($userId, $userScore);
        
        // check if the score is a new high score for the player
        if($database->getUserHighScore($userId) < $userScore) {
            
            // set the new high score
            $database->setUserHighScore($userId, $userScore);

            $cache = new CacheManager();

            // check to see if the new high score belongs in the top X of scores
            if($cache->getLowestLeaderScore() < $userScore) {
                $cache->reassignLeaders($database);
            }
        }

        $database->close();
    }

}

?>
