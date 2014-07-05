<?php

class GameService {

    // setup
    // CREATE DATABASE high_scores;

    public function recordScore($params) {
        if(isset($params['user_id']) && isset($params['user_score'])) {
            $userId = $params['user_id'];
            $userScore = $params['user_score'];

            $gameController = new GameController();
            $gameController->recordScore($userId, $userScore);
        } else {
            jlog("missing required params!");
        }
    }
    
    public function sayHello($name) {
        arr_log($name);

        // this will show us if mysql is installed with a PDO driver
        arr_log(PDO::getAvailableDrivers());

        $user = 'hs_admin';
        $pass = 'kixeye_interview';

        try {
            $db = new PDO("mysql:host=localhost;dbname=high_scores", $user, $pass);
            $result = $db->query('SELECT * FROM high_score');
            while($row = $result->fetch()) {
                arr_log($row);
            }
        } catch(PDOException $exception) {
            arr_log($exception);
        }
    }

    public function getJSONObject($input) {
        if(isset($input['data']) && json_decode($input['data'], true) != null) {
            $params = json_decode($input['data'], true);
        }
        return $params;
    }

}

?>
