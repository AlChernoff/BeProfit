<?php

// get database connection
require_once    'Database.php';

// instantiate order object
require_once 'Order.php';

// instantiate api object
require_once  'Api.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$api = new Api('https://www.become.co/api/rest/test/',"tzinch",'r#eD21mA%gNU');

$data = $api->fetchData();
print_r ($data);