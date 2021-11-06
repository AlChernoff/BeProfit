<?php

// get database connection
require_once __DIR__ . './../Database.php';

// instantiate order object
require_once __DIR__ . './../Order.php';

// instantiate api object
require_once __DIR__ . './../Api.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

$api = new Api('https://www.become.co/api/rest/test/',"tzinch",'r#eD21mA%gNU');

$data = $api->fetchData();
// get posted data

if(isset($data['msg']) == 'Internal Server Error') {
    throw new Exception('Sorry, we can\'t fech results now, please try later!');
} else if (isset($data['error']) == 'Throttled') {
    throw new Exception( 'API Limit is reached, please try in 5 minutes!');
}
foreach ($data['data'] as $row) {
    $order->insertData($row);
}
