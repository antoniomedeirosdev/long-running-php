<?php

class OrderController
{
    public const JSON_SERVER_URL = 'http://json-server';

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function listOrders() {
        $orders = json_decode(file_get_contents(self::JSON_SERVER_URL . '/orders'), true);
        $arrOrders = [];
        foreach ($orders as $order) {
            $arrOrders[] = new Order($order['id'], $order['status']);
        }

        include __DIR__ . '/../view/list_orders.php';
    }
}