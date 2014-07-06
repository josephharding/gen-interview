<?php

class ReportController {
    
    public function getLeaders() {
        $cache = new CacheManager();
        $result = $cache->getLeaders();
        if($result == null) {
            $cache->reassignLeaders(new DBManager());
            $result = $cache->getLeaders();
        }
        return $result;
    }

    public function getNumUsers() {
        $database = new DBManager();
        return $database->getNumRowsInUsers();
    }

    public function getUniqueUsersToday() {
        return $this->getUniqueUsersInRange(new DateTime(date('Y/m/d')), new DateTime(date('Y/m/d H:i:s')));
    }

    public function getUniqueUsersOnDay($startTimeString) {
        $startTime = new DateTime($startTimeString);
        $endTime = new DateTime($startTimeString);
        return $this->getUniqueUsersInRange($startTime, $endTime->modify('+1 day'));
    }

    private function getUniqueUsersInRange($dateTimeStart, $dateTimeEnd) {
        $database = new DBManager();
        $start = $dateTimeStart->format('Y-m-d H:i:s');
        $end = $dateTimeEnd->format('Y-m-d H:i:s');
        return $database->getNumUniqueUsersInRange($start, $end);
    }

}

?>
