<?php

class GameService {

    // setup
    // CREATE DATABASE high_scores;

    /*
    
    usage: curl -H 'content-type:application/json' -d '{"user_id":"112", "user_score":"110"}' http://josephharding.net/highscore/service/game/recordScore
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
            jlog("missing required params!");
        }
        return $result;
    }


    /*
    usage: curl http://josephharding.net/highscore/service/game/resetCache
    */
    public function resetCache() {
        $controller = new GameController();
        return $controller->resetCache();
    }
    
}

?>
