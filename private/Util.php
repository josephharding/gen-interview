<?php

// could use this pattern for overloading like in java
// $args = func_get_args();

function p_arr_log($prefix, $content) {
    jlog($prefix);
    arr_log($content);
}

function arr_log($content) {
    ob_start();
    var_dump($content);
    $contents = ob_get_contents();
    ob_end_clean();
    jlog($contents);
}

function jlog($content) {
    error_log($content);
}

?>
