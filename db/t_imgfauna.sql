CREATE OR REPLACE TABLE eecs647.imgfauna(
    code      CHAR (10) NOT NULL,
    fpath     CHAR (128) NOT NULL,
    FOREIGN KEY (code) REFERENCES fauna (code),
    FOREIGN KEY (fpath) REFERENCES image (fpath),
    PRIMARY KEY (code, fpath)
);

insert into eecs647.imgfauna(
    code,
    fpath
) VALUES (
    "ELK",
    'Elk-Wapiti_-_Banff.jpg'
);

-- insert into eecs647.imgfauna(
--     code,
--     fpath
-- ) VALUES (
--     "BLKBEAR",
--     '585px-A_Florida_Black_Bear.jpg'
-- );

insert into eecs647.imgfauna(
    code,
    fpath
) VALUES (
    "BLKBEAR",
    'Black_bear_sow_with_cub,_Tower_Fall.jpg'
);

insert into eecs647.imgfauna(
    code,
    fpath
) VALUES (
    "COYOTE",
    '2009-Coyote-Yosemite.jpg'
);
