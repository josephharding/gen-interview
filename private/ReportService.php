<?php

class ReportService {
    
    public function getLeaders() {
        
        $cache = new CacheManager();
        echo arr_log($cache->getLeaders());
    }

    public function getNumUsersDuringTime($params) {

        // do validation here
        $startTime = $params['startTime'];
        $endTime = $params['endTime'];

        return null;
    }

    public function getNumUsers() {
        
    }

}

?>
