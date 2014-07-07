<?php

/*

Util.php

Collection of common utility functions and logging functions
we may use across the whole project

*/

class Util {
    
    /*
    returns true if date is of format yyyy/mm/dd AND these 
    values pass PHP's built in checkdate function else false
    */
    public static function validDate($date) {
        $result = false;
        $date_arr  = explode('/', $date);
        if (count($date_arr) == 3) {
            $year = $date_arr[0];
            $month = $date_arr[1];
            $day = $date_arr[2];
            if(strlen($year) == 4 && strlen($month) == 2 && strlen($day) == 2) {
                if (checkdate($month, $day, $year)) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public static function p_arr_log($prefix, $content) {
        Util::hs_log($prefix);
        Util::arr_log($content);
    }

    public static function arr_log($content) {
        ob_start();
        var_dump($content);
        $contents = ob_get_contents();
        ob_end_clean();
        Util::hs_log($contents);
    }

    public static function hs_log($content) {
        error_log($content);
    }

}

?>
