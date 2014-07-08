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
        $dbManager = new DBManager();
        $cache = new CacheManager();

        // add an entry to our score table
        $result = $dbManager->addScoreEntry($userId, $userScore);

        // get the user's high score from cache
        $currentHighScore = $cache->getUserHighScore($userId);
        
        // if the value doesn't exist, we need to calculate and set it
        if($currentHighScore === false) {
            // then get the user's high score from persistence
            $currentHighScore = $dbManager->getUserHighScore($userId);
            // set this score in cache for next time
            $cache->setUserHighScore($userId, $currentHighScore);
        } else if($userScore > $currentHighScore) {
            // if the cached value does exist but it's less than the current score, reset it
            $cache->setUserHighScore($userId, $userScore);
        }
        
        // check to see if we need to reassign leaders
        // this could technically be moved to be inside both the if and else if statements above
        // but in the interest of fewer lines of code and clearer logic keeping it here
        if($cache->getLowestLeaderScore() < $userScore) {
            $cache->reassignLeaders($dbManager);
        }

        return $result;
    }

    /**
     * Resets the cache 
     *
     */
    public function resetCache() {
        $cache = new CacheManager();
        return $cache->resetCache(new DBManager());
    }

}
