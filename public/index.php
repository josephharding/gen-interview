<?php

// TODO
// we should stress test the input handling here, pass in all sorts of garbage
// should the app respond to the user with error codes? is it more secure to do nothing?

/*
header('HTTP/1.0 404 Not Found');
echo "<h1>404 Not Found</h1>";
echo "The page that you have requested could not be found.";
exit();
*/

define("SERVICE_NAME", "service");

define("PATH_INDEX_LAYER", 2);
define("PATH_INDEX_SERVICE", 3);
define("PATH_INDEX_METHOD", 4);

require '../../highscore/inc.php';

// expecting requests with this format:
// http://josephharding.net/highscore_public/service/test/sayHello?params='{"arg1":"val1","arg2":"val2"}'
// in chrome: http://josephharding.net/highscore/service/test/sayHello?data={"name":"Joe"}

$requestURL = parse_url($_SERVER["REQUEST_URI"]);
$pathArray = explode("/", $requestURL['path']);

if(is_string($pathArray[PATH_INDEX_LAYER]) && strtolower($pathArray[PATH_INDEX_LAYER]) === SERVICE_NAME) {
    
    // this transformation makes the first letter of the service name a capital and appends 'Service' to the end
    $serviceClassName = ucwords($pathArray[PATH_INDEX_SERVICE]) . "Service";
    $serviceMethodName = $pathArray[PATH_INDEX_METHOD];
    
    if(class_exists($serviceClassName)) {
        $service = new $serviceClassName();
        if(method_exists($service, $serviceMethodName)) {
            $params = array();
            if(isset($requestURL['query'])) {
                parse_str($requestURL['query'], $params);
                if(isset($params['data']) && json_decode($params['data'], true) != null) {
                    $params = json_decode($params['data'], true);
                }
            }
            call_user_func(array($service, $serviceMethodName), $params); 
        } else {
            jlog("no method with name $serviceMethodName");
        }
    } else {
        jlog("no class with name $serviceClassName");
    }
} else {
    jlog("bad url provided: " . $requestURL['path']);
}

?>
