<?php

// get database connection
require_once  'Database.php';

// instantiate order object
require_once  'Order.php';

// instantiate api object
require_once  'Api.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// get posted data
$data = json_decode("php://input",true);


if ($data == null) {
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Json is invalid"));
} else {
    if(isset($data['daily'])) {
        $order->getStatisticsByDay($data);
    } else if(isset($data['weekly'])) {
        $order->getStatisticsByWeek($data);
    }  else if(isset($data['monthly'])) {
        $order->getStatisticsByMonth($data);
    } else {
        $order->getStatistics($data);
    }

}
