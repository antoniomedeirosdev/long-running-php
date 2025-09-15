<?php

class OrderController
{
    public const JSON_SERVER_URL = 'http://json-server';

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generateOrders()
    {
        $howMany = $_POST['how_many'];

        for ($i = 0; $i < $howMany; $i++) {
            $order = new Order();
            $data = $order->toArray();

            $options = array(
                'http' => array(
                    'method' => 'POST',
                    'content' => json_encode($data),
                    'header' => "Content-Type: application/json\r\n" .
                        "Accept: application/json\r\n"
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents(self::JSON_SERVER_URL . '/orders', false, $context);
            //$response = json_decode($result);
            //var_dump($response); die();
        }

        $this->listOrders();
    }

    public function listOrders()
    {
        $orders = json_decode(file_get_contents(self::JSON_SERVER_URL . '/orders'), true);
        $arrOrders = [];
        foreach ($orders as $order) {
            $arrOrders[] = new Order($order['id'], $order['status']);
        }

        include __DIR__ . '/../view/list_orders.php';
    }
}