CREATE OR REPLACE TABLE eecs647.fauna(
    code      CHAR (10) UNIQUE NOT NULL,
    name      CHAR (32) NOT NULL, 
    class     CHAR (32) NOT NULL,
    `order`     CHAR (32) NOT NULL,
    suborder  CHAR (32) NOT NULL,
    family    CHAR (32) NOT NULL,
    subfamily CHAR (32) NOT NULL,
    genus     CHAR (32) NOT NULL,
    homerange_max DECIMAL ( 4 ),
    homerange_min DECIMAL ( 4 ),
    weight_m      DECIMAL ( 4, 0 ),
    weight_f      DECIMAL ( 4, 0 ),
    height_cm     DECIMAL ( 4, 0 ),
    length_cm     DECIMAL ( 4, 0 ),
    lifespan      DECIMAL ( 3, 0 ),
    endangered TINYINT UNSIGNED,
    fdesc TEXT,
    PRIMARY KEY (code)
);

insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "PUMA",
    "Cougar",
    "Mammalia",
    "Carnivora",
    "Feliformia",
    "Felidae",
    "Felinae",
    "Puma",
    350,
    25,
    150,
    121,
    75,
    240,
    10,
    0,
    "The cougar is a large cat species found in the Americas. It often preys on deer and small game. Although their modern habitat is limited to just a small fraction of their historical range, they are not on the endangered species list."
);

insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "BLKBEAR",
    "American Black Bear",
    "Mammalia",
    "Carnivora",
    "N/A",
    "Usidae",
    "N/A",
    "Ursus",
    59, 
    10,
    551,
    375,
    105,
    200,
    18, 
    0,
    "The American Black Bear is the most common species of bear on the continent. A medium-sized omnivore, the black bear is frequently seen both in forested areas as well as scrounging in suburbs."
);


insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "ELK",
    "Elk (Wapiti)",
    "Mammalia",
    "Artiodactyla",
    "N/A",
    "Cervidae",
    "Cervinae",
    "Cervus",
    16,
    3,
    1100,
    625,
    150,
    270,
    13,
    0,
    "American Elk are found in forest and forest-edge habitats throughout the North American continent. As one of the largest species of deer, they are prized by hunters, whose activies also fund the majority of conservation efforts."
);


insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "WHTDEER",
    "White-Tailed Deer",
    "Mammalia",
    "Artiodactyla",
    "N/A",
    "Cervidae",
    "Capreolinae",
    "Odocoileus",
    20, 
    1,
    300,
    200,
    120,
    220,
    5,
    0,
    "The White-Tailed Deer is a medium-sized deer found throughout most of North America."
);


insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "MULEDEER",
    "Mule Deer (Blacktail Deer)",
    "Mammalia",
    "Artiodactyla",
    "N/A",
    "Cervidae",
    "Capreolinae",
    "Odocoileus",
    30,
    1,
    450,
    150,
    106,
    210,
    10,
    0,
    "Found in the Great Plains, Rocky Mountains, and the southwest, Mule Deer are the larger and more robust of the Odocoileus genus."
);

insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "COYOTE",
    "Coyote",
    "Mammalia",
    "Carnivora",
    "N/A",
    "Canidae",
    "N/A",
    "Canis",
    16,
    4,
    44,
    40,
    60,
    135,
    12,
    0,
    "A small predator, the Coyote feeds on deer, rabbits, rodents, birds, and other small game."
);

insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "COYOTE",
    "Coyote",
    "Mammalia",
    "Carnivora",
    "N/A",
    "Canidae",
    "N/A",
    "Canis",
    16,
    4,
    44,
    40,
    60,
    135,
    12,
    0,
    "A small predator, the Coyote feeds on deer, rabbits, rodents, birds, and other small game."
);

insert into eecs647.fauna (
      code
    , name
    , class
    , `order`
    , suborder
    , family
    , subfamily
    , genus
    , homerange_max
    , homerange_min
    , weight_m
    , weight_f
    , height_cm
    , length_cm
    , lifespan
    , endangered
    , fdesc
) VALUES (
    "GATOR",
    "American Alligator",
    "Reptilia",
    "Crocodilia",
    "N/A",
    "Alligatoridae",
    "Alligatorinae",
    "Alligator",
    12,
    1,
    1000,
    1000, 
    38,
    400,
    70,
    0,
    "The American Alligator is native to the southeastern United States. Its diet consists of fish, birds, reptiles, amphibians, and mammals."
);



DELIMITER ;
