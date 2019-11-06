CREATE TABLE IF NOT EXISTS version
(
    id      INTEGER     NOT NULL PRIMARY KEY AUTO_INCREMENT,
    version VARCHAR(50) NOT NULL
);

INSERT INTO version (id, version)
VALUES (NULL, '1.0.2');
INSERT INTO version (id, version)
VALUES (NULL, '1.0.3');