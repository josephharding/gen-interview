<?php

class ReportService {
    
    /*
    usage: curl -H 'content-type:application/json' http://josephharding.net/highscore/service/report/getLeaders
    */
    public function getLeaders() {        
        $controller = new ReportController();
        return $controller->getLeaders();
    }

    /*
    usage: curl -H 'content-type:application/json' -d '{"day":"yyyy/mm/dd"}' http://josephharding.net/highscore/service/report/getNumUsersOnDay
    */
    public function getNumUsersOnDay($params) {
        $result = false;
        if(isset($params['day']) && Util::validDate($params['day'])) {
            $controller = new ReportController();
            $result = $controller->getUniqueUsersOnDay($params['day']);
        } else {
            jlog("params missing or incorrectly formatted");
        }
        return $result;
    }

    /*
    usage: curl -H 'content-type:application/json' http://josephharding.net/highscore/service/report/getNumUsersToday
    */
    public function getNumUsersToday() {
        $controller = new ReportController();
        return $controller->getUniqueUsersToday();
    }

    /*
    usage: curl -H 'content-type:application/json' http://josephharding.net/highscore/service/report/getTotalNumUsers
    */
    public function getTotalNumUsers() {
        $controller = new ReportController();
        return $controller->getNumUsers();
    }

}

?>
