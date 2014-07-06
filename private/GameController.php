<?php

class GameController {

    public function recordScore($userId, $userScore) {        
        $database = new DBManager();

        // make sure this user has an entry in our users database (this would be done elsewhere, maybe on user registration)
        $this->checkUserEntry($database, $userId);

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
    }

    public function resetCache() {
        $cache = new CacheManager();
        return $cache->resetCache();
    }

    private function checkUserEntry($database, $userId) {
        if($database->getUserEntry($userId) == null) {
            $database->setUserEntry($userId);
        }
    }

}

?>
