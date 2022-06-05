CREATE OR REPLACE TABLE eecs647.parks(
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    pname CHAR (128) NOT NULL,     -- PARK NAME
    pdesc TEXT NOT NULL,           -- PARK DESCRIPTION
    pstat CHAR (2)   NOT NULL,     -- PARK STATE CODE
    fnded DATE,                    -- PARK FOUNDED DATE
    sqrmi DECIMAL (7, 2) NOT NULL, -- PARK SQUARE MILEAGE
    vsytd DECIMAL (7, 0) NOT NULL, -- Visitors YTD
    vslfy DECIMAL (7, 0) NOT NULL, -- Visitors last fiscal year
    t_prc DECIMAL (5, 2) NOT NULL, -- TICKET PRICE
    alch  BOOLEAN NOT NULL DEFAULT 0, -- ALLOW ALCOHOL?
    camp  BOOLEAN NOT NULL DEFAULT 0, -- ALLOW CAMPING?
    guns  BOOLEAN NOT NULL DEFAULT 0, -- ALLOW GUNS?
    PRIMARY KEY (id)
);
