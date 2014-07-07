<?php

/*
ReportService.php

Responsible for handing reporting requests
*/

class ReportService {
    
    /**
     * Returns the leading users in order by score (highest to lowest)
     *
     * usage: curl http://josephharding.net/highscore/service/report/getLeaders
     *
     * @return array an array of user ids in order from largest high score to least
     */
    public function getLeaders() {        
        $controller = new ReportController();
        return $controller->getLeaders();
    }

    /**
     * Gets the number of unique users on the day requested
     * 
     * usage: curl -H 'content-type:application/json' -d '{"day":"yyyy/mm/dd"}' http://josephharding.net/highscore/service/report/getNumUsersOnDay 
     *
     * @param JSONObject @params    the container object holding the params:
     *                              string day a string of the form YYYY/MM/DD which is 
     *                              the day we are requesting the number of users
     *
     * @return int  the number of users on the day requested
     */
    public function getNumUsersOnDay($params) {
        $result = false;
        if(isset($params['day']) && Util::validDate($params['day'])) {
            $controller = new ReportController();
            $result = $controller->getUniqueUsersOnDay($params['day']);
        } else {
            Util::hs_log("params missing or incorrectly formatted");
        }
        return $result;
    }

    /**
     * Gets the number of users who have played today
     * 
     * usage: curl http://josephharding.net/highscore/service/report/getNumUsersToday
     *
     * @return int  the number of users who have submitted scores today
     */
    public function getNumUsersToday() {
        $controller = new ReportController();
        return $controller->getUniqueUsersToday();
    }

    /**
     * Get the total number of users who have submitted scores over all time
     *
     * usage: curl http://josephharding.net/highscore/service/report/getTotalNumUsers
     *
     * @return int the total number of users who have submitted scores
     */
    public function getTotalNumUsers() {
        $controller = new ReportController();
        return $controller->getNumUsers();
    }

}

?>
