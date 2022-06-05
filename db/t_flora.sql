CREATE OR REPLACE TABLE eecs647.flora(
    name CHAR (32) NOT NULL, 
    fdesc TEXT NOT NULL,
    endangered TINYINT UNSIGNED NOT NULL,
    invasive BOOLEAN NOT NULL,
    poisonous BOOLEAN NOT NULL,
    PRIMARY KEY (name)
);
