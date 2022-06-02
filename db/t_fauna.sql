CREATE OR REPLACE TABLE a637m351.fauna(
    name CHAR (32) NOT NULL, 
    weight_m DECIMAL,
    weight_f DECIMAL,
    lifespan DECIMAL,
    endangered BOOLEAN,

    desc CHAR (256) DEFAULT 'NO DESCRIPTION PROVIDED',
    PRIMARY KEY (name)
);