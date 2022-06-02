CREATE OR REPLACE TABLE a637m351.parks(
    id SMALLINT NOT NULL AUTO_INCREMENT,
    name  CHAR (128) NOT NULL,     -- PARK NAME
    state CHAR (2)   NOT NULL,     -- PARK STATE CODE
    t_adt DECIMAL (3, 2) NOT NULL, -- TICKET PRICE, ADULT
    t_chd DECIMAL (3, 2) NOT NULL, -- TICKET PRICE, CHILD
    ldsct DECIMAL (0, 2) NOT NULL, -- LOCAL VISITOR DISCOUNT %
    szn_s DATE                     -- SEASON START DATE
    szn_e DATE                     -- SEASON END DATE
    alch  BOOL                     -- ALLOW ALCOHOL?
    drug  BOOL                     -- ALLOW CANNABIS?
    camp  BOOL                     -- ALLOW CAMPING?
    guns  BOOL                     -- ALLOW GUNS?
    desc  CHAR (256) DEFAULT 'NO DESCRIPTION PROVIDED', -- PARK DESCRIPTION
    PRIMARY KEY (id)
);
