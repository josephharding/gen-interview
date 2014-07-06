<?php

class CacheManager {
    
    private $m_memcache;

    // move this into a define
    private $m_numLeaders = 10;

    const LOWEST_LEADER_KEY = "lowest_leader_score";

    const LEADER_KEY_PREFIX = "leader_";

    public function __construct() {
        $this->m_memcache = new Memcache;
        $this->m_memcache->connect('localhost', 11211) or die ("Could not connect");
    }

    public function getLowestLeaderScore() {
        $result = 0;
        $lowestLeader = $this->m_memcache->get(self::LOWEST_LEADER_KEY);
        if(isset($lowestLeader)) {
            $result = $lowestLeader;
        }
        return $result;
    }

    public function reassignLeaders($database) {
        $userIds = $database->getTopUserIdsByScore($this->m_numLeaders);
        $numLeaders = count($userIds);
        for($index = 0; $index < $numLeaders; $index++) {
            $this->m_memcache->set($this->getLeaderKey($index), $userIds[$index]);
        }
        if($numLeaders > 0) {
            $this->m_memcache->set(self::LOWEST_LEADER_KEY, $userIds[$numLeaders - 1]);
        }
    }

    public function getLeaders() {
        $result = array();
        for($index = 0; $index < $this->m_numLeaders; $index++) {
            $val = $this->m_memcache->get($this->getLeaderKey($index));
            if($val != false) {
                $result[] = $val;
            }
        }
        return $result;
    }

    // looks like delete always returns false...
    public function resetCache() {
        $this->m_memcache->delete(self::LOWEST_LEADER_KEY);
        for($index = 0; $index < $this->m_numLeaders; $index++) {
            $this->m_memcache->delete($this->getLeaderKey($index));
        }
    }

    private function getLeaderKey($leaderPlace) {
        return self::LEADER_KEY_PREFIX . $leaderPlace;
    }

}

?>
