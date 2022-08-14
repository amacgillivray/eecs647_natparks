CREATE OR REPLACE TABLE eecs647.image(
    fpath CHAR (128) UNIQUE NOT NULL,     -- URL OF THE IMAGE UNDER /_img/
    author CHAR (128) DEFAULT 'N/A',
    license CHAR (128) DEFAULT 'N/A',
    dattkn DATE,
    natw DECIMAL (5,0) NOT NULL, -- img width
    nath DECIMAL (5,0) NOT NULL, -- img height
    lfauna CHAR (10),
    lpark CHAR (10),
    mfr CHAR (128) DEFAULT 'N/A', -- Camera Manufacturer
    `mod` CHAR (64) DEFAULT 'N/A', -- Camera `mod`el
    exposure CHAR (16) DEFAULT 'N/A',
    fnum CHAR (16) DEFAULT 'N/A',
    iso CHAR (16) DEFAULT 'N/A',
    foclen CHAR (16) DEFAULT 'N/A',
    idesc TEXT NOT NULL,           -- IMG idesc
    FOREIGN KEY (lfauna) REFERENCES fauna (code),
    FOREIGN KEY (lpark) REFERENCES parks (id),
    PRIMARY KEY (fpath)
);

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license
-- ) VALUES (
--     'BLKBEAR',
--     'EVRGLD',
--     '585px-A_Florida_Black_Bear.jpg',
--     585,
--     480,
--     "An inquisitive Florida black bear has triggered a remote camera set by biologists. The bear is in the sand pine scrub of the Ocala National Forest, which supports the highest density population of black bears in North America.",
--     "Florida Fish and Wildlife Conservation Commission",
--     '2012-03-15 16:46',
--     'Public Domain'
-- );


-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     "COYOTE",
--     "YOSMT",
--     '2009-Coyote-Yosemite.jpg',
--     565,
--     656,
--     'Coyote from Yosemite National Park, California',
--     'Yathin S. Krishnappa',
--     '2009-01-08 04:25',
--     'CC BY-SA',
--     "NIKON CORPORATION",
--     "D300",
--     "1/160 sec.",
--     "f/6.3",
--     "ISO-400",
--     "300 mm"
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     "COYOTE",
--     "YOSMT",
--     'Coyote_portrait.jpg',
--     1280,
--     731,
--     'Canis latrans',
--     'Christopher Bruno',
--     '2004-02-13',
--     'CC BY-SA 3.0',
--     "Canon",
--     "Canon EOS Digital Rebel",
--     "1/500 sec",
--     "f/6.3",
--     "ISO-800",
--     "142 mm"
-- );


-- -- insert into eecs647.image(
-- --     fpath,
-- --     natw,
-- --     nath,
-- --     idesc, 
-- --     author,
-- --     dattkn,
-- --     license,
-- --     mfr,
-- --     `mod`,
-- --     exposure,
-- --     fnum, 
-- --     iso, 
-- --     foclen
-- -- ) VALUES (
-- --     'Antilocapra_americana.jpg'
-- -- );


-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     "BLKBEAR",
--     "YLWSTN",
--     'Black_bear_sow_with_cub,_Tower_Fall.jpg',
--     5760,
--     3840,
--     "Black bear sow with cub near Tower Fall; Neal Herbert; May 2015; Catalog #20108d",
--     "Yellowstone National Park",
--     "2015-05-19 18:40",
--     "Public Domain",
--     "Canon",
--     "Canon EOS 5D Mark III",
--     "1/200 sec",
--     "f/5.6",
--     "ISO-1000",
--     '365 mm'
-- );


-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     "ELK",
--     NULL,
--     'Elk-Wapiti_-_Banff.jpg',
--     4928,
--     3264,
--     'Elk in Banff National Park, Canada',
--     'Jakub Fryš',
--     '2018-10-15 16:06',
--     'CC BY-SA 4.0',
--     'NIKON CORPORATION',
--     'NIKON D7000',
--     '1/80 sec',
--     'f/6.3',
--     'ISO-1600',
--     '600 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     "BLKBEAR",
--     NULL,
--     'Grand_Tetons_black_bear.jpg',
--     1024,
--     683,
--     "This female black bear had a small cub with her but the cub was so small it is hidden in the undergrowth. The cub was bounding around everywhere and was impossible to capture in the darkness of these woods. You can just see the top of the cub\'s back near the tree to the left of the mother bear.\nA week prior to this image being taken, the mother had two cubs, but a local ranger told us that the other cub was recently killed by a grizzly bear.\nThe pair are fattening up on huckleberries.",
--     "Alan Vernon",
--     '2010-09-18 09:00',
--     'CC BY 2.0',
--     'Canon',
--     'Canon EOS 7D',
--     '1/125 sec',
--     'f/5.6',
--     'ISO-800',
--     '140 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     NULL,
--     'YOSMT',
--     'Half_Dome_from_Glacier_Point,_Yosemite_NP_-_Diliff.jpg',
--     4752,
--     2988,
--     'Half Dome as viewed from Glacier Point, Yosemite National Park, California, United States.',
--     'David Iliff',
--     '2013-05-16 03:38',
--     NULL,
--     'CC BY-SA 3.0',
--     NULL,
--     NULL,
--     '1/200 sec',
--     'f/8',
--     'ISO-320',
--     '63 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     'MULEDEER',
--     NULL,
--     'Mule_buck_elk_creek_m_myatt_(5489214303).jpg',
--     2100,
--     1500,
--     'Mule deer buck at Elk Creek.',
--     'Oregon Department of Fish & Wildlife',
--     '2010-12-20 12:04',
--     'CC BY-SA 2.0',
--     'Canon',
--     'Canon EOS DIGITAL REBEL XTi',
--     '1/400 sec',
--     'f/8',
--     'ISO-200',
--     '180 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license
-- ) VALUES (
--     'WHTDEER',
--     NULL,
--     'White-tailed_deer.jpg',
--     2700,
--     2051,
--     'A white-tailed deer.',
--     'US Dept. of Agriculture / Scott Bauer',
--     '2005-07-29 20:38',
--     'Public Domain'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     'PUMA',
--     'EVRGLD',
--     'Florida_Panther_NPSPhoto,_Rodney_Cammauf.png',
--     1280,
--     853,
--     'Florida Panther',
--     'Everglades NPS / Rodney Cammauf',
--     '2005-01-26 15:14',
--     'Public Domain',
--     'Canon',
--     'Canon EOS-1D Mark II',
--     '1/250 sec',
--     'f/5.6',
--     'ISO-640',
--     '500 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     'GATOR',
--     NULL,
--     'am-gator.jpg',
--     2048,
--     1365,
--     'An American Alligator rests in the Six Mile Cyprus Slough Preserve near Fort Myers, Florida.',
--     'Dennis Church',
--     '2019-10-11',
--     'CC BY-NC-ND 2.0',
--     'Canon',
--     'Canon EOS-80D',
--     '1/640 sec',
--     'f/7.1',
--     'ISO-2000',
--     '484 mm'
-- );

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (
--     'BISON',
--     "YLWSTN",
--     'am-bison.png',
--     2048,
--     1365,
--     'Bison walking in the snow near Tower Junction',
--     'National Park Service / Jacob W. Frank',
--     '2017-01-07',
--     'Public Domain',
--     'Canon',
--     'Canon EOS 5DS',
--     '1/640 sec',
--     'f/6.3',
--     'ISO-400',
--     '400 mm'
-- );

insert into eecs647.image(
    lfauna,
    lpark,
    fpath,
    natw,
    nath,
    idesc, 
    author,
    dattkn,
    license,
    mfr,
    `mod`,
    exposure,
    fnum, 
    iso, 
    foclen
) VALUES (
    'MULEDEER',
    "YLWSTN",
    'mule-deer.png',
    2048,
    1365,
    'Mule deer buck, Garnet Hill Loop',
    'National Park Service / Neal Herbert',
    '2016-11-11',
    'Public Domain',
    'Canon',
    'Canon EOS 5D Mark III',
    '1/100 sec',
    'f/5.6',
    'ISO-800',
    '350 mm'
);

-- insert into eecs647.image(
--     lfauna,
--     lpark,
--     fpath,
--     natw,
--     nath,
--     idesc, 
--     author,
--     dattkn,
--     license,
--     mfr,
--     `mod`,
--     exposure,
--     fnum, 
--     iso, 
--     foclen
-- ) VALUES (

-- );
