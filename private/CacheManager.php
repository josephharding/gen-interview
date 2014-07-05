<?php

class CacheManager {
    
    private $m_memcache;

    private $m_numLeaders = 10;

    public function __construct() {
        $this->m_memcache = new Memcache;
        $this->m_memcache->connect('localhost', 11211) or die ("Could not connect");
    }

    public function getLowestLeaderScore() {
        $result = -1;

        $lowestLeader = $this->m_memcache->get('lowest_leader_score');
        if(isset($lowestLeader)) {
            $result = $lowestLeader;
        }

        return $result;
    }

    public function reassignLeaders($database) {
        $users = $database->getTopUsersByScore($this->m_numLeaders);
        arr_log($users);

        $numLeaders = count($users);
        for($index = 0; $index < $numLeaders; $index++) {
            $this->m_memcache->set("leader_" . $index, $users[$index]["user_id"]);
        }
        if($numLeaders > 0) {
            $this->m_memcache->set("lowest_leader_score", $users[$numLeaders - 1]["high_score"]);
        }
    }

    public function getLeaders() {
        $result = array();
        for($index = 0; $index < $this->m_numLeaders; $index++) {
            $val = $this->m_memcache->get("leader_" . $index);
            if($val != false) {
                $result[] = $val;
            }
        }
        return $result;
    }

}

?>
