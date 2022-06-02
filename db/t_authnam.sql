
CREATE TABLE IF NOT EXISTS a637m351.authnam
(
    code  CHAR (10) NOT NULL,
    fname CHAR (63) NOT NULL,
    expl  CHAR (255),
    PRIMARY KEY (code)
);

CREATE TABLE IF NOT EXISTS a637m351.userauth
(
      user CHAR (10) NOT NULL
    , auth CHAR (10) NOT NULL
    , FOREIGN KEY (auth)
        REFERENCES authnam(code)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    , PRIMARY KEY (user, auth)
);

CREATE OR REPLACE PROCEDURE 
    a637m351.getuseraut ( IN arguser CHAR (10) ) 
READS SQL DATA
BEGIN 
SELECT a637m351.userauth.auth 
FROM   a637m351.userauth
WHERE  user = arguser
ORDER BY a637m351.userauth.auth;
END// 

CREATE OR REPLACE PROCEDURE a637m351.grant ( 
    IN arguser CHAR (10), 
    IN argauth CHAR (10)
) 
MODIFIES SQL DATA
BEGIN 
INSERT 
    INTO a637m351.userauth ( user, auth ) 
    VALUES ( arguser, argauth );
END// 

CREATE OR REPLACE PROCEDURE a637m351.revoke ( 
    IN arguser CHAR (10), 
    IN argauth CHAR (10)
) 
MODIFIES SQL DATA
BEGIN 
DELETE
    FROM a637m351.userauth
    WHERE user = arguser 
      AND auth = argauth;
END// 