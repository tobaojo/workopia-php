<?php

use Framework\Database;

$config = require basePath('config/db.php');
$db = new Database($config);
$listings = $db->query('SELECT * from listings')->fetchAll();

loadView('listings/index', ['listings' => $listings]);
