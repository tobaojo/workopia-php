<?php

$config = require basePath('config/db.php');
$db = new Database($config);
$listings = $db->query('SELECT * from listings LIMIT 6')->fetchAll();

loadView('home', [
    'listings' => $listings
]);
