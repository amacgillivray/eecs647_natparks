<?php

setcookie(
    'lid',
    '',
    time()-86400,
    '/'
);

header('Location: ./login.php');

