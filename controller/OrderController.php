<?php

class OrderController
{
    public const JSON_SERVER_URL = 'http://json-server/orders';

    private static $instance = null;
    private $alert = '';

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAlert()
    {
        return $this->alert;
    }

    public function setAlert($alert)
    {
        $this->alert = $alert;
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
            $result = file_get_contents(self::JSON_SERVER_URL, false, $context);
            //$response = json_decode($result);
            //var_dump($response); die();
        }

        $this->setAlert('Orders generated!');

        $this->listOrders();
    }

    public function listOrders()
    {
        $orders = json_decode(file_get_contents(self::JSON_SERVER_URL), true);

        $arrOrders = [];
        foreach ($orders as $order) {
            $arrOrders[] = Order::fromArray($order);
        }

        include __DIR__ . '/../view/list_orders.php';
    }

    public function processOrders()
    {
        $arrId = $_POST['id'];

        foreach ($arrId as $id) {
            $order = new Order($id);
            $this->remoteProcess($order);
            $this->updateOrder($order);
        }

        $this->setAlert('Orders processed!');

        $this->listOrders();
    }

    private function remoteProcess(Order $order)
    {
        // Sleep randomly between 2 and 10 seconds to simulate talking to another system
        $randomNumber = mt_rand(2, 10);
        sleep($randomNumber);

        // Also define a random new status
        $order->setStatus(Order::randomStatus());
    }

    private function updateOrder(Order $order)
    {
        $data = $order->toArray();
        $jsonData = json_encode($data);

        $curl = curl_init(self::JSON_SERVER_URL . '/' . $order->getId());

        // Set cURL options
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData) // Optional but good practice
        ]);

        curl_exec($curl);

        curl_close($curl);
    }
}