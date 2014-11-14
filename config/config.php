<?php

// database configuration
App::config('db', array(
    'host'     => 'localhost',
    'database' => '',
    'username' => '',
    'password' => '',
    'port'     => 3306
));

// init language strings
Locale::initLanguages('en', 'sr');

?>