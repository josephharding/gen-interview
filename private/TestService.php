<?php

class TestService {

    // setup
    // CREATE DATABASE high_scores;
    // CREATE USER 'hs_admin'@'localhost' IDENTIFIED BY 'kixeye_interview';
    // use high_scores;
    // CREATE TABLE high_score (user_id VARCHAR(20), user_score VARCHAR(20));
    // GRANT ALL PRIVILEGES ON high_scores.high_score TO 'hs_admin'@'localhost';
    // 
    // insert into high_score values('123', '100');

    public function sayHello($name) {
        echo "params, " . $name;

        $jsonName = json_decode($name, true);
        echo "\n Your name is " . $jsonName['name'];
        arr_log($jsonName);

        // this will show us if mysql is installed with a PDO driver
        arr_log(PDO::getAvailableDrivers());

        $user = 'hs_admin';
        $pass = 'kixeye_interview';

        try {
            $db = new PDO("mysql:host=localhost;dbname=high_scores", $user, $pass);
            $result = $db->query('SELECT * FROM high_score');
            while($row = $result->fetch()) {
                arr_log($row);
            }
        } catch(PDOException $exception) {
            arr_log($exception);
        }
    }

}

?>
