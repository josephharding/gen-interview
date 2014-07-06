USE high_scores;

DROP TABLE IF EXISTS users;
CREATE TABLE users (user_id VARCHAR(20), high_score INTEGER(20), PRIMARY KEY (user_id));
INSERT INTO users (user_id, high_score) VALUES ('123', '0');
INSERT INTO users (user_id, high_score) VALUES ('101', '0');

DROP TABLE IF EXISTS scores;
CREATE TABLE scores (user_id VARCHAR(20), score INTEGER(20), ts_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT INTO scores (user_id, score) VALUES ('123', '100');
INSERT INTO scores (user_id, score) VALUES ('101', '80');
INSERT INTO scores (user_id, score) VALUES ('101', '70');
INSERT INTO scores (user_id, score) VALUES ('101', '90');

DROP USER 'hs_admin'@'localhost';
CREATE USER 'hs_admin'@'localhost' IDENTIFIED BY 'kixeye_interview';
GRANT ALL PRIVILEGES ON high_scores.scores TO 'hs_admin'@'localhost';
GRANT ALL PRIVILEGES ON high_scores.users TO 'hs_admin'@'localhost';
