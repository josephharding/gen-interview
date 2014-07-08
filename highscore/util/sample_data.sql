CREATE DATABASE jh_scores;
USE jh_scores;

DROP TABLE IF EXISTS scores;
CREATE TABLE scores (user_id VARCHAR(20), score INTEGER(20), ts_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT INTO scores (user_id, score, ts_create) VALUES ('Bob', '100', '2014-07-05 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Amanda', '80', '2014-07-06 23:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Steve', '70', '2014-07-04 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Mary', '90', '2014-07-03 02:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Mary', '10', '2014-07-03 23:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Joe', '40', '2014-07-04 01:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Joe', '120', '2014-07-02 04:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Jane', '70', '2014-07-01 21:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Dan', '90', '2014-07-03 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Phil', '40', '2014-07-02 05:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Kevin', '30', '2014-07-04 03:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Sean', '20', '2014-07-05 15:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Sean', '10', '2014-07-06 16:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Eve', '10', '2014-07-06 18:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Eve', '290', '2014-07-03 07:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Mark', '150', '2014-07-02 08:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Debbie', '100', '2014-07-01 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Mark', '60', '2014-07-01 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Noob', '0', '2014-07-01 20:00:00');
INSERT INTO scores (user_id, score, ts_create) VALUES ('Steve', '200', '2014-07-01 20:00:00');

DROP USER 'hs_admin'@'localhost';
CREATE USER 'hs_admin'@'localhost' IDENTIFIED BY 'kixeye_interview';
GRANT ALL PRIVILEGES ON jh_scores.scores TO 'hs_admin'@'localhost';
