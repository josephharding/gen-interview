<?php

/*
GameService.php

Handles input validation and controller routing for game logic requests
*/

class GameService {

    /**
     * Record a user's score
     *
     * usage: curl -H 'content-type:application/json' -d '{"user_id":"112", "user_score":"110"}' http://josephharding.net/highscore/service/game/recordScore
     * @param JSONObject $params    contains the parameters:
     *                              string  user_id     the unique user id
     *                              int     user_score  the score to record 
     * @return boolean  true if the recording was successful, false otherwise 
     */
    public function recordScore($params) {
        $result = false;
        if(isset($params['user_id']) && isset($params['user_score'])) {
            $result = true;
            $userId = $params['user_id'];
            $userScore = $params['user_score'];

            $controller = new GameController();
            $controller->recordScore($userId, $userScore);
        } else {
            Util::hs_log("missing required params!");
        }
        return $result;
    }

    /**
     * Reset the cache
     *
     * usage: curl http://josephharding.net/highscore/service/game/resetCache
     */
    public function resetCache() {
        $controller = new GameController();
        $controller->resetCache();
    }
    
}

?>
