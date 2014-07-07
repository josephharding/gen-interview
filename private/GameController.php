<?php

/*

GameController.php

Handles the implementaion of game functions

*/

class GameController {

    /**
     *
     * Record a score for the given user. If the score is a new
     * high score than record that as well. If the score is a new
     * leading score among all players then record that too.
     *
     * @param string $userId       the user's unique id
     * @param string $userScore    the user's score
     */
    public function recordScore($userId, $userScore) {        
        $database = new DBManager();
        $cache = new CacheManager();

        // add an entry to our score table
        $database->addScoreEntry($userId, $userScore);

        // get the user's high score from cache
        $currentHighScore = $cache->getUserHighScore($userId);
        if($currentHighScore === false) {
            // if there is no cached score check to make sure the user is in our database and add if not
            $this->conditionallySetUserEntry($database, $userId);

            // then get the user's high score from persistence
            $currentHighScore = $database->getUserHighScore($userId);

            // set this score in cache for next time
            $cache->setUserHighScore($userId, $currentHighScore);
        }
        
        // check if the score is a new high score for the player
        if($userScore > $currentHighScore) {
            
            // set the new high score
            $database->setUserHighScore($userId, $userScore);
            $cache->setUserHighScore($userId, $userScore);

            // check to see if the new high score belongs in the top X of scores
            if($cache->getLowestLeaderScore() < $userScore) {
                $cache->reassignLeaders($database);
            }
        }
    }

    /**
     * Resets the cache 
     *
     */
    public function resetCache() {
        $cache = new CacheManager();
        return $cache->resetCache(new DBManager());
    }

    /*
     * Makes a user entry if one doesn't already exist
     *
     * @param DBManager $database   a database manager object
     * @param string    @userId     unique player id
     */
    private function conditionallySetUserEntry($database, $userId) {
        if($database->getUserEntry($userId) == null) {
            $database->setUserEntry($userId);
        }
    }

}

?>
