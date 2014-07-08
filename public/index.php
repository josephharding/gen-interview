<?php

/*
index.php, the request router

This file is the only publicly accessible component of the High Scores backend.
The router parses the request uri and forwards it to the appropriate service method.
*/

define("SERVICE_NAME", "service");

define("PATH_INDEX_LAYER", 2);
define("PATH_INDEX_SERVICE", 3);
define("PATH_INDEX_METHOD", 4);

require '../../highscore/inc.php';

$requestURL = parse_url($_SERVER['REQUEST_URI']);
$pathArray = explode("/", $requestURL['path']);

if(isset($pathArray[PATH_INDEX_LAYER]) && isset($pathArray[PATH_INDEX_SERVICE]) && 
    isset($pathArray[PATH_INDEX_METHOD]) && strtolower($pathArray[PATH_INDEX_LAYER]) == SERVICE_NAME) {
    
    // this transformation makes the first letter of the service name a capital and appends 'Service' to the end
    $serviceClassName = ucwords($pathArray[PATH_INDEX_SERVICE]) . "Service";
    $serviceMethodName = $pathArray[PATH_INDEX_METHOD];
    
    // the router does not echo out responses in the case of missing services or method names
    // in order to give attackers as little information as possible
    if(class_exists($serviceClassName)) {
        $service = new $serviceClassName();
        if(method_exists($service, $serviceMethodName)) {
            
            // not every request will include POST data, but those that do will be expected in JSON format
            $params = array();
            if(isset($HTTP_RAW_POST_DATA) && $HTTP_RAW_POST_DATA != "") {
                if(json_decode($HTTP_RAW_POST_DATA, true) != null) {
                    $params = json_decode($HTTP_RAW_POST_DATA, true);
                }
            }

            // make the service call
            echo json_encode(call_user_func(array($service, $serviceMethodName), $params)) . "\n"; 
        } else {
            Util::hs_log("no method with name $serviceMethodName on class $serviceClassName");
        }
    } else {
        Util::hs_log("no class with name $serviceClassName");
    }
} else {
    Util::hs_log("bad url provided: " . $requestURL['path']);
}

