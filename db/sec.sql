DELIMITER //

CREATE OR REPLACE TABLE eecs647.users(
    uname CHAR(10) NOT NULL,
    pwrd CHAR(128) NOT NULL,
    PRIMARY KEY (uname)
);

CREATE OR REPLACE TABLE eecs647.authnam
(
    code  CHAR (10) NOT NULL,
    fname CHAR (63) NOT NULL,
    expl  CHAR (255),
    PRIMARY KEY (code)
);

CREATE OR REPLACE TABLE eecs647.userauth
(
      user CHAR (10) NOT NULL
    , auth CHAR (10) NOT NULL
    , FOREIGN KEY (user)
        REFERENCES users(uname)
        ON DELETE CASCADE
    , FOREIGN KEY (auth)
        REFERENCES authnam(code)
        ON DELETE CASCADE
    , PRIMARY KEY (user, auth)
);

CREATE OR REPLACE PROCEDURE eecs647.crtusr ( 
    IN arguser CHAR (10), 
    IN argpwrd CHAR (128)
) 
MODIFIES SQL DATA
BEGIN 
INSERT 
    INTO eecs647.users ( uname, pwrd ) 
    VALUES ( arguser, argpwrd );
END// 

CREATE OR REPLACE PROCEDURE eecs647.dltusr ( 
    IN arguser CHAR (10)
) 
MODIFIES SQL DATA
BEGIN 
DELETE
    FROM  eecs647.users
    WHERE user = arguser 
END// 

CREATE OR REPLACE PROCEDURE 
    eecs647.getusrauth ( IN arguser CHAR (10) ) 
READS SQL DATA
BEGIN 
SELECT eecs647.userauth.auth 
FROM   eecs647.userauth
WHERE  user = arguser
ORDER BY eecs647.userauth.auth;
END// 

CREATE OR REPLACE PROCEDURE eecs647.grant ( 
    IN arguser CHAR (10), 
    IN argauth CHAR (10)
) 
MODIFIES SQL DATA
BEGIN 
INSERT 
    INTO eecs647.userauth ( user, auth ) 
    VALUES ( arguser, argauth );
END// 

CREATE OR REPLACE PROCEDURE eecs647.revoke ( 
    IN arguser CHAR (10), 
    IN argauth CHAR (10)
) 
MODIFIES SQL DATA
BEGIN 
DELETE
    FROM eecs647.userauth
    WHERE user = arguser 
      AND auth = argauth;
END// 

DELIMITER ;
