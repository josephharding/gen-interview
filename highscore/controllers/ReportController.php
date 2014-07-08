<?php

/*

ReportController.php

Responsible for implementing reporting calls

*/

class ReportController {
    
    /**
     * Returns the leading users in order by score
     *
     * @return array the user ids of the leading users by score, in order from largest score to smallest 
     */
    public function getLeaders() {
        $cache = new CacheManager();
        $result = $cache->getLeaders();
        if(count($result) == 0) {
            $cache->reassignLeaders(new DBManager());
            $result = $cache->getLeaders();
        }
        return $result;
    }
    
    /**
     * Gets the total number of users
     *
     * @return int  number of users
     */
    public function getNumUsers() {
        $database = new DBManager();
        return $database->getNumRowsInUsers();
    }

    /**
     * Returns the number of users that have submitted scores today
     *
     * @return int  number of users who have submitted scores today
     */
    public function getUniqueUsersToday() {
        return $this->getUniqueUsersInRange(new DateTime(date('Y/m/d')), new DateTime(date('Y/m/d H:i:s')));
    }

    /**
     * Returns the number fo users who have submitted scores on any given day
     *
     * @param string $startTimeString   the day for which we will return the number of users
     *
     * @return int  number of users who have submitted scores on the given day
     */
    public function getUniqueUsersOnDay($startTimeString) {
        $startTime = new DateTime($startTimeString);
        $endTime = new DateTime($startTimeString);
        return $this->getUniqueUsersInRange($startTime, $endTime->modify('+1 day'));
    }
    
    /**
     * given a start and end date return the number of unique users who have submitted scores
     * 
     * @param DateTime $dateTimeStart   object representing the start time for the range
     * @param DateTime $dateTimeEnd     object representing the end time for the range
     *
     * @return int number of users who have submitted scores in the range requested
     */
    private function getUniqueUsersInRange($dateTimeStart, $dateTimeEnd) {
        $database = new DBManager();
        $start = $dateTimeStart->format('Y-m-d H:i:s');
        $end = $dateTimeEnd->format('Y-m-d H:i:s');
        return $database->getNumUniqueUsersInRange($start, $end);
    }

}
