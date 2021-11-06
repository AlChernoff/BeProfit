<?php




class Order
{

    // database connection and table name
    public $conn;

    // object properties
    public $order_ID;
    public $shop_ID;
    public $closed_at;
    public $created_at;
    public $updated_at;
    public $total_price;
    public $subtotal_price;
    public $total_weight;
    public $total_tax;
    public $currency;
    public $financial_status;
    public $total_discounts;
    public $name;
    public $fulfillment_status;
    public $country;
    public $province;
    public $total_items;
    public $total_order_shipping_cost;
    public $total_order_handling_cost;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function insertData($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {
            // make sure data is not empty
            if (
                isset($data['shop_ID']) &&
                isset($data['order_ID'])&&
                isset($data['created_at']) &&
                isset($data['updated_at']) &&
                isset($data['total_price']) &&
                isset($data['subtotal_price']) &&
                isset($data['total_weight']) &&
                isset($data['total_tax']) &&
                isset($data['currency']) &&
                isset($data['financial_status']) &&
                isset($data['total_discounts']) &&
                isset($data['name']) &&
                isset($data['country']) &&
                isset($data['province'])  &&
                isset($data['total_items']) &&
                isset($data['total_order_shipping_cost']) &&
                isset($data['total_order_handling_cost'])
            ) {



                // set order property values
                $this->order_ID = $data['order_ID'];
                $this->shop_ID = $data['shop_ID'];
                $data['closed_at'] = $data['closed_at'] != '' ? $data['closed_at'] : null ;
                $this->closed_at =  $data['closed_at'];
                $this->created_at = $data['created_at'];
                $this->updated_at = $data['updated_at'];
                $this->total_price = $data['total_price'];
                $this->subtotal_price = $data['subtotal_price'];
                $this->total_weight = $data['total_weight'];
                $this->total_tax = $data['total_tax'];
                $this->currency = $data['currency'];
                $this->financial_status = $data['financial_status'];
                $this->total_discounts = $data['total_discounts'];
                $this->name = $data['name'];
                $this->country = $data['country'];
                $this->province = $data['province'];
                $this->total_items = $data['total_items'];
                $this->total_order_shipping_cost = $data['total_order_shipping_cost'];
                $this->total_order_handling_cost = $data['total_order_handling_cost'];

                // create the order
                if ($this->create()) {

                    // set response code - 201 created
                    http_response_code(201);

                    // tell the user
                    echo json_encode(array("message" => "Order was created."));
                }

                // if unable to create the product, tell the user
                else {

                    // set response code - 503 service unavailable
                    http_response_code(503);

                    // tell the user
                    echo json_encode(array("message" => "Unable to create order."));
                }
            }

                        // tell the user data is incomplete
            else {

                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
            }

            // $sql = "INSERT INTO orders (user_id,followers_count, favourites_count,user_metrics_create_date) VALUES (?,?,?,?)";
            // $query = $db->prepare($sql);
            // foreach ($response as $key => $user) {
            //     $query->execute([$user['user']['id'], $user['user']['followers_count'], $user['user']['favourites_count'], date("Y-m-d: H-i-s", strtotime($user['user']['created_at']))]);

            // }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function updateData($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {
            if (
                isset($data['total_tax']) or
                isset($data['total_order_shipping_cost']) or
                isset($data['total_order_handling_cost'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Unable to update order. Those fields are immutable."));
            }

            if (
                !isset($data['order_ID'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Order ID must be provided!"));
            }

            $fetch_order = "SELECT * FROM `orders` WHERE order_ID=:order_ID";
            $fetch_stmt = $this->conn->prepare($fetch_order);
            $fetch_stmt->bindValue(':order_ID', $data['order_ID']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                // make sure data is not empty
                $data['shop_ID'] =isset($data['shop_ID']) ? $data['shop_ID'] : $row['shop_ID'];
                $data['order_ID'] = isset($data['order_ID']) ? $data['order_ID'] : $row['order_ID'];
                $data['created_at'] = isset($data['created_at']) ? $data['created_at'] : $row['created_at'];
                $data['updated_at'] = isset($data['updated_at']) ? $data['updated_at'] : $row['updated_at'];
                $data['total_price'] = isset($data['total_price']) ? $data['total_price'] : $row['total_price'];
                $data['subtotal_price'] = isset($data['subtotal_price']) ? $data['subtotal_price'] : $row['subtotal_price'];
                $data['total_weight'] = isset($data['total_weight']) ? $data['total_weight'] : $row['total_weight'];
                $data['total_tax'] = isset($data['total_tax']) ? $data['total_tax'] : $row['total_tax'];
                $data['currency'] = isset($data['currency']) ? $data['currency'] : $row['currency'];
                $data['financial_status'] = isset($data['financial_status']) ? $data['financial_status'] : $row['financial_status'];
                $data['total_discounts'] = isset($data['total_discounts']) ? $data['total_discounts'] : $row['total_discounts'];
                $data['name'] = isset($data['name']) ? $data['name'] : $row['name'];
                $data['country'] = isset($data['country']) ? $data['country'] : $row['country'];
                $data['province'] = isset($data['province']) ? $data['province'] : $row['province'];
                $data['total_items'] = isset($data['total_items']) ? $data['total_items'] : $row['total_items'];
                $data['total_order_shipping_cost'] = isset($data['total_order_shipping_cost']) ? $data['total_order_shipping_cost'] : $row['total_order_shipping_cost'];
                $data['total_order_handling_cost'] = isset($data['total_order_handling_cost']) ? $data['total_order_handling_cost'] : $row['total_order_handling_cost'];




                    // set order property values
                    $this->order_ID = $data['order_ID'];
                    $this->shop_ID = $data['shop_ID'];
                    $data['closed_at'] = $data['closed_at'] != '' ? $data['closed_at'] : null ;
                    $this->closed_at =  $data['closed_at'];
                    $this->created_at = $data['created_at'];
                    $this->updated_at = $data['updated_at'];
                    $this->total_price = $data['total_price'];
                    $this->subtotal_price = $data['subtotal_price'];
                    $this->total_weight = $data['total_weight'];
                    $this->total_tax = $data['total_tax'];
                    $this->currency = $data['currency'];
                    $this->financial_status = $data['financial_status'];
                    $this->total_discounts = $data['total_discounts'];
                    $this->name = $data['name'];
                    $this->country = $data['country'];
                    $this->province = $data['province'];
                    $this->total_items = $data['total_items'];
                    $this->total_order_shipping_cost = $data['total_order_shipping_cost'];
                    $this->total_order_handling_cost = $data['total_order_handling_cost'];

                    // create the order
                    if ($this->update()) {

                        // set response code - 201 created
                        http_response_code(201);

                        // tell the user
                        echo json_encode(array("message" => "Order was Updated."));
                    }

                    // if unable to create the product, tell the user
                    else {

                        // set response code - 503 service unavailable
                        http_response_code(503);

                        // tell the user
                        echo json_encode(array("message" => "Unable to update order."));
                    }
                }

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function deleteData($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['order_ID'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Order ID must be provided!"));
            }

            $fetch_order = "SELECT * FROM `orders` WHERE order_ID=:order_ID";
            $fetch_stmt = $this->conn->prepare($fetch_order);
            $fetch_stmt->bindValue(':order_ID', $data['order_ID']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                // set order property values
                $this->order_ID = $data['order_ID'];

                // create the order
                if ($this->delete()) {

                    // set response code - 201 created
                    http_response_code(201);

                    // tell the user
                    echo json_encode(array("message" => "Order was Deleted."));
                }

                // if unable to create the product, tell the user
                else {

                    // set response code - 503 service unavailable
                    http_response_code(503);

                    // tell the user
                    echo json_encode(array("message" => "Unable to delete order."));
                }
            }

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getData($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['order_ID'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Order ID must be provided!"));
            }

            $fetch_order = "SELECT * FROM `orders` WHERE order_ID=:order_ID";
            $fetch_stmt = $this->conn->prepare($fetch_order);
            $fetch_stmt->bindValue(':order_ID', $data['order_ID']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);


                return json_encode($row);
            }

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getStatistics($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['start_date']) or
                !isset($data['end_date'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Dates must be provided!"));
            }

            $statistics = [];

            $net_sales = 'select SUM(total_price) as net_sales from orders where financial_status  not in ("paid", "partially_paid")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"';
            $fetch_stmt = $this->conn->prepare($net_sales);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            $fetch_stmt->debugDumpParams();
            if ($fetch_stmt->rowCount() > 0){
                $net_sales = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['net_sales'] = $net_sales;
            } else {
                $statistics['net_sales'] = 0;
            }

            $refunds= 'select SUM(total_price) as refunds from orders where financial_status   in ("refunded")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"';
            $fetch_stmt = $this->conn->prepare($refunds);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $refunds = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics["refunds"] = $refunds;
            } else {
                $statistics["refunds"] = 0;
            }

            $shipping = 'select SUM(total_order_shipping_cost) as shipping from orders where fullfilment_status   in ("unfulfilled")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date)"';
            $fetch_stmt = $this->conn->prepare($shipping);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $shipping = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['shipping'] = $shipping;
            } else {
                $statistics['shipping'] = 0;
            }

            $statistics['gross_profit'] = floatval($statistics['net_sales']) - floatval($statistics['shipping']);
            $statistics['gross_margin'] = floatval($statistics['net_sales']/100) * ($statistics['gross_profit']);
            $summary = json_encode($statistics);
            return $summary;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getStatisticsByDay($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['start_date']) or
                !isset($data['end_date'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Dates must be provided!"));
            }

            $statistics = [];

            $net_sales = 'select DAY(created_at) as day,SUM(total_price) as net_sales from orders where financial_status  not in ("paid", "partially_paid")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY DAY(created_at)';
            $fetch_stmt = $this->conn->prepare($net_sales);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            $fetch_stmt->debugDumpParams();
            if ($fetch_stmt->rowCount() > 0){
                $net_sales = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['net_sales'] = $net_sales;
            } else {
                $statistics['net_sales'] = 0;
            }

            $refunds= 'select DAY(created_at) as day,SUM(total_price) as refunds from orders where financial_status   in ("refunded")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY DAY(created_at)';
            $fetch_stmt = $this->conn->prepare($refunds);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $refunds = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics["refunds"] = $refunds;
            } else {
                $statistics["refunds"] = 0;
            }

            $shipping = 'select DAY(created_at) as day,SUM(total_order_shipping_cost) as shipping from orders where fullfilment_status   in ("unfulfilled")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date)"
                GROUP BY DAY(created_at)';
            $fetch_stmt = $this->conn->prepare($shipping);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $shipping = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['shipping'] = $shipping;
            } else {
                $statistics['shipping'] = 0;
            }

            $statistics['gross_profit'] = floatval($statistics['net_sales']) - floatval($statistics['shipping']);
            $statistics['gross_margin'] = floatval($statistics['net_sales']/100) * ($statistics['gross_profit']);
            $summary = json_encode($statistics);
            return $summary;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getStatisticsByWeek($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['start_date']) or
                !isset($data['end_date'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Dates must be provided!"));
            }

            $statistics = [];

            $net_sales = 'select WEEK(created_at) as week,SUM(total_price) as net_sales from orders where financial_status  not in ("paid", "partially_paid")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY WEEK(created_at)';
            $fetch_stmt = $this->conn->prepare($net_sales);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            $fetch_stmt->debugDumpParams();
            if ($fetch_stmt->rowCount() > 0){
                $net_sales = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['net_sales'] = $net_sales;
            } else {
                $statistics['net_sales'] = 0;
            }

            $refunds= 'select WEEK(created_at) as week,SUM(total_price) as refunds from orders where financial_status   in ("refunded")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY WEEK(created_at)';
            $fetch_stmt = $this->conn->prepare($refunds);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $refunds = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics["refunds"] = $refunds;
            } else {
                $statistics["refunds"] = 0;
            }

            $shipping = 'select WEEK(created_at) as week,SUM(total_order_shipping_cost) as shipping from orders where fullfilment_status   in ("unfulfilled")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date)"
                GROUP BY WEEK(created_at)';
            $fetch_stmt = $this->conn->prepare($shipping);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $shipping = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['shipping'] = $shipping;
            } else {
                $statistics['shipping'] = 0;
            }

            $statistics['gross_profit'] = floatval($statistics['net_sales']) - floatval($statistics['shipping']);
            $statistics['gross_margin'] = floatval($statistics['net_sales']/100) * ($statistics['gross_profit']);
            $summary = json_encode($statistics);
            return $summary;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function getStatisticsByMonth($data) {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        try {

            if (
                !isset($data['start_date']) or
                !isset($data['end_date'])
            ) {
                // set response code - 400 bad request
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Dates must be provided!"));
            }

            $statistics = [];

            $net_sales = 'select MONTH(created_at) as month,SUM(total_price) as net_sales from orders where financial_status  not in ("paid", "partially_paid")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY MONTH(created_at)';
            $fetch_stmt = $this->conn->prepare($net_sales);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            $fetch_stmt->debugDumpParams();
            if ($fetch_stmt->rowCount() > 0){
                $net_sales = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['net_sales'] = $net_sales;
            } else {
                $statistics['net_sales'] = 0;
            }

            $refunds= 'select MONTH(created_at) as month,SUM(total_price) as refunds from orders where financial_status   in ("refunded")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date"
                GROUP BY MONTH(created_at)';
            $fetch_stmt = $this->conn->prepare($refunds);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $refunds = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics["refunds"] = $refunds;
            } else {
                $statistics["refunds"] = 0;
            }

            $shipping = 'select MONTH(created_at) as month,SUM(total_order_shipping_cost) as shipping from orders where fullfilment_status   in ("unfulfilled")
                and CAST(created_at AS DATE) between "start_date=:start_date" and "end_date=:end_date)"
                GROUP BY MONTH(created_at)';
            $fetch_stmt = $this->conn->prepare($shipping);
            $fetch_stmt->bindValue(':start_date', $data['start_date']);
            $fetch_stmt->bindValue(':end_date', $data['end_date']);
            $fetch_stmt->execute();
            if ($fetch_stmt->rowCount() > 0){
                $shipping = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $statistics['shipping'] = $shipping;
            } else {
                $statistics['shipping'] = 0;
            }

            $statistics['gross_profit'] = floatval($statistics['net_sales']) - floatval($statistics['shipping']);
            $statistics['gross_margin'] = floatval($statistics['net_sales']/100) * ($statistics['gross_profit']);
            $summary = json_encode($statistics);
            return $summary;

        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function create(){
        // query to insert record
        $query = "INSERT INTO orders
        SET
        order_ID=:order_ID, shop_ID=:shop_ID, closed_at=:closed_at, created_at=:created_at, updated_at=:updated_at,
        total_price=:total_price, subtotal_price=:subtotal_price, total_weight=:total_weight, total_tax=:total_tax,
        currency=:currency,financial_status=:financial_status, total_discounts=:total_discounts, name=:name,
        fulfillment_status=:fulfillment_status,country=:country,province=:province,total_items=:total_items
        ,total_order_shipping_cost=:total_order_shipping_cost,total_order_handling_cost=:total_order_handling_cost
        ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->order_ID=htmlspecialchars(strip_tags($this->order_ID));
        $this->shop_ID=htmlspecialchars(strip_tags($this->shop_ID));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
        $this->total_price=htmlspecialchars(strip_tags($this->total_price));
        $this->subtotal_price=htmlspecialchars(strip_tags($this->subtotal_price));
        $this->total_weight=htmlspecialchars(strip_tags($this->total_weight));
        $this->total_tax=htmlspecialchars(strip_tags($this->total_tax));
        $this->currency=htmlspecialchars(strip_tags($this->currency));
        $this->financial_status=htmlspecialchars(strip_tags($this->financial_status));
        $this->total_discounts=htmlspecialchars(strip_tags($this->total_discounts));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->fulfillment_status=htmlspecialchars(strip_tags($this->fulfillment_status));
        $this->country=htmlspecialchars(strip_tags($this->country));
        $this->province=htmlspecialchars(strip_tags($this->province));
        $this->total_items=htmlspecialchars(strip_tags($this->total_items));
        $this->total_order_shipping_cost=htmlspecialchars(strip_tags($this->total_order_shipping_cost));
        $this->total_order_handling_cost=htmlspecialchars(strip_tags($this->total_order_handling_cost));

        // bind values
        $stmt->bindParam(":order_ID", $this->order_ID);
        $stmt->bindParam(":shop_ID", $this->shop_ID);
        $stmt->bindParam(":closed_at", $this->closed_at);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":total_price", $this->total_price);
        $stmt->bindParam(":subtotal_price", $this->subtotal_price);
        $stmt->bindParam(":total_weight", $this->total_weight);
        $stmt->bindParam(":total_tax", $this->total_tax);
        $stmt->bindParam(":currency", $this->currency);
        $stmt->bindParam(":financial_status", $this->financial_status);
        $stmt->bindParam(":total_discounts", $this->total_discounts);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":fulfillment_status", $this->fulfillment_status);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":province", $this->province);
        $stmt->bindParam(":total_items", $this->total_items);
        $stmt->bindParam(":total_order_shipping_cost", $this->total_order_shipping_cost);
        $stmt->bindParam(":total_order_handling_cost", $this->total_order_handling_cost);


        // execute query
        if($stmt->execute()){
            return true;
        }



        return false;

    }

    public function update(){
        // query to insert record
        $query = "UPDATE orders
        SET
        order_ID=:order_ID, shop_ID=:shop_ID, closed_at=:closed_at, created_at=:created_at, updated_at=:updated_at,
        total_price=:total_price, subtotal_price=:subtotal_price, total_weight=:total_weight, total_tax=:total_tax,
        currency=:currency,financial_status=:financial_status, total_discounts=:total_discounts, name=:name,
        fulfillment_status=:fulfillment_status,country=:country,province=:province,total_items=:total_items
        ,total_order_shipping_cost=:total_order_shipping_cost,total_order_handling_cost=:total_order_handling_cost
        where order_ID=:order_ID
        ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->order_ID=htmlspecialchars(strip_tags($this->order_ID));
        $this->shop_ID=htmlspecialchars(strip_tags($this->shop_ID));
        $this->created_at=htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
        $this->total_price=htmlspecialchars(strip_tags($this->total_price));
        $this->subtotal_price=htmlspecialchars(strip_tags($this->subtotal_price));
        $this->total_weight=htmlspecialchars(strip_tags($this->total_weight));
        $this->total_tax=htmlspecialchars(strip_tags($this->total_tax));
        $this->currency=htmlspecialchars(strip_tags($this->currency));
        $this->financial_status=htmlspecialchars(strip_tags($this->financial_status));
        $this->total_discounts=htmlspecialchars(strip_tags($this->total_discounts));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->fulfillment_status=htmlspecialchars(strip_tags($this->fulfillment_status));
        $this->country=htmlspecialchars(strip_tags($this->country));
        $this->province=htmlspecialchars(strip_tags($this->province));
        $this->total_items=htmlspecialchars(strip_tags($this->total_items));
        $this->total_order_shipping_cost=htmlspecialchars(strip_tags($this->total_order_shipping_cost));
        $this->total_order_handling_cost=htmlspecialchars(strip_tags($this->total_order_handling_cost));

        // bind values
        $stmt->bindParam(":order_ID", $this->order_ID);
        $stmt->bindParam(":shop_ID", $this->shop_ID);
        $stmt->bindParam(":closed_at", $this->closed_at);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);
        $stmt->bindParam(":total_price", $this->total_price);
        $stmt->bindParam(":subtotal_price", $this->subtotal_price);
        $stmt->bindParam(":total_weight", $this->total_weight);
        $stmt->bindParam(":total_tax", $this->total_tax);
        $stmt->bindParam(":currency", $this->currency);
        $stmt->bindParam(":financial_status", $this->financial_status);
        $stmt->bindParam(":total_discounts", $this->total_discounts);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":fulfillment_status", $this->fulfillment_status);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":province", $this->province);
        $stmt->bindParam(":total_items", $this->total_items);
        $stmt->bindParam(":total_order_shipping_cost", $this->total_order_shipping_cost);
        $stmt->bindParam(":total_order_handling_cost", $this->total_order_handling_cost);


        // execute query
        if($stmt->execute()){
            return true;
        }

        echo "\nPDO::errorInfo():\n";
        print_r($stmt->queryString);
        print_r($stmt->errorInfo());


        return false;

    }

    public function delete(){
        // query to insert record
        $query = "DELETE from orders
        where order_ID=:order_ID
        ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->order_ID=htmlspecialchars(strip_tags($this->order_ID));

        // bind values
        $stmt->bindParam(":order_ID", $this->order_ID);


        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
}

?>