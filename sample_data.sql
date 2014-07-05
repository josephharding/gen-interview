USE high_scores;

DROP TABLE IF EXISTS high_score;
CREATE TABLE high_score (user_id VARCHAR(20), user_score VARCHAR(20));
INSERT INTO high_score (user_id, user_score) VALUES ('123', '100');
INSERT INTO high_score (user_id, user_score) VALUES ('101', '80');

DROP USER 'hs_admin'@'localhost';
CREATE USER 'hs_admin'@'localhost' IDENTIFIED BY 'kixeye_interview';
GRANT ALL PRIVILEGES ON high_scores.high_score TO 'hs_admin'@'localhost';
