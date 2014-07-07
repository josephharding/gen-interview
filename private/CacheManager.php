<?php

/*

CacheManager.php

Responsible for returning cached values and settings cached values.

Currently uses a memcache implementation.

*/


// how many leader users should be stored
define('NUM_LEADERS', 10);

class CacheManager {
   
    // memcache keys and partial keys
    const LOWEST_LEADER_KEY = "lowest_leader_score";

    const LEADER_KEY_PREFIX = "leader_";

    const USER_HIGH_SCORE_KEY_PREFIX = "high_score_";

    private $m_memcache;

    public function __construct() {
        $this->m_memcache = new Memcache;
        if(!$this->m_memcache->connect('localhost', 11211)) {
            Util::hs_log("memcache failed to connect!");
        }
    }
    
    /**
     * return the user's high score from cache
     * 
     * @param string $userId the user's unqiue id
     */
    public function getUserHighScore($userId) {
        return $this->m_memcache->get($this->getUserHighScoreKey($userId));
    }

    /**
     * set the user's high score
     *
     * @param string    $userId     the user's unique id
     * @param int       $highScore  the uer's new high score
     */
    public function setUserHighScore($userId, $highScore) {
        $this->m_memcache->set($this->getUserHighScoreKey($userId), $highScore);
    }

    /**
     * The the lowest leader score, or the score to beat to make it into
     * the leader bracket
     *
     * @return int  the lowest leader score
     */
    public function getLowestLeaderScore() {
        $result = 0;
        $lowestLeader = $this->m_memcache->get(self::LOWEST_LEADER_KEY);
        if($lowestLeader != false) {
            $result = $lowestLeader;
        }
        return $result;
    }

    /**
     * reassign the leader cached values based on the top user ids by score
     *
     * @param DBManager $database   the database manager injected into the caching class
     *                              is the source of truth for data in this project
     */
    public function reassignLeaders($database) {
        $userIds = $database->getTopUserIdsByScore(NUM_LEADERS);
        $numLeaders = count($userIds);
        for($index = 0; $index < $numLeaders; $index++) {
            $this->m_memcache->set($this->getLeaderKey($index), $userIds[$index]);
        }
        if($numLeaders > 0) {
            $this->m_memcache->set(self::LOWEST_LEADER_KEY, $userIds[$numLeaders - 1]);
        }
    }

    /**
     * get the cached leaders ids from the cache
     *
     * @return array    the cached leader user ids
     */
    public function getLeaders() {
        $result = array();
        for($index = 0; $index < NUM_LEADERS; $index++) {
            $val = $this->m_memcache->get($this->getLeaderKey($index));
            if($val != false) {
                $result[] = $val;
            }
        }
        return $result;
    }

    /**
     * Reset the cache, delete all cached values maintained by this class
     *
     * @param DBManager $database   the database manager which providesall the user ids
     */
    public function resetCache($database) {
        $this->m_memcache->delete(self::LOWEST_LEADER_KEY);
        for($index = 0; $index < NUM_LEADERS; $index++) {
            $this->m_memcache->delete($this->getLeaderKey($index));
        }
        $userIds = $database->getAllUserIds();
        foreach($userIds as $userId) {
            $this->m_memcache->delete($this->getUserHighScoreKey($userId));
        }
    }

    /**
     * helper function maintains the correct format for the leader key
     * 
     * @param int $leaderPlace  the place (0, for zeroth place, 1, for first place) for the leader
     *
     * @return string   returns the properly formatted leader key
     */
    private function getLeaderKey($leaderPlace) {
        return self::LEADER_KEY_PREFIX . $leaderPlace;
    }
    
    /**
     * helper function maintains the correct format for the high score key
     * 
     * @param string $userId    the user id of the user who's cached score value we are getting
     *
     * @return string   returns the properly formatted high score key
     */
    private function getUserHighScoreKey($userId) {
        return self::USER_HIGH_SCORE_KEY_PREFIX . $userId;
    }

}

?>
