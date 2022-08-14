CREATE OR REPLACE TABLE eecs647.parkfauna(
    id   CHAR (10) NOT NULL,
    code CHAR (10) NOT NULL,
    FOREIGN KEY (id) REFERENCES parks (id) ON DELETE RESTRICT,
    FOREIGN KEY (code) REFERENCES fauna (code) ON DELETE RESTRICT,
    PRIMARY KEY (id, code)
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'BISON'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'EVRGLD',
    'BLKBEAR'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'BLKBEAR'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'BLKBEAR'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'BLKBEAR'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'EVRGLD',
    'COYOTE'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'COYOTE'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'COYOTE'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'COYOTE'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'ELK'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'ELK'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'ELK'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'EVRGLD',
    'GATOR'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'MULEDEER'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'MULEDEER'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'MULEDEER'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'EVRGLD',
    'PUMA'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'PUMA'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'PUMA'
);

insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'EVRGLD',
    'WHTDEER'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'RCKMTN',
    'WHTDEER'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YLWSTN',
    'WHTDEER'
);
insert into eecs647.parkfauna(
    id, 
    code
) VALUES (
    'YOSMT',
    'WHTDEER'
);

