<?php

class OrderController
{
    public const APP_URL = 'http://localhost:8080';
    public const JSON_SERVER_URL = 'http://json-server/orders';

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
            $result = file_get_contents(self::JSON_SERVER_URL, false, $context);
            //$response = json_decode($result);
            //var_dump($response); die();
        }

        $_SESSION['message'] = 'Orders generated!';

        header('Location: ' . self::APP_URL);
        exit();
    }

    public function listOrders()
    {
        $orders = json_decode(file_get_contents(self::JSON_SERVER_URL), true);

        $arrOrders = [];
        foreach ($orders as $order) {
            $arrOrders[] = Order::fromArray($order);
        }

        include __DIR__ . '/../view/list_orders.php';

        if (!empty($_SESSION['message'])) {
            unset($_SESSION['message']);
        }
    }

    public function processOrders()
    {
        $arrId = $_POST['id'];

        $queueKey = Order::uuidgen();
        $queue = new OrderQueue($queueKey);
        foreach ($arrId as $id) {
            $order = new Order($id);
            $queue->enqueue($order);
        }
        $size = count($arrId);
        $queue->setInitialSize($size);
        $queue->setCurrentSize($size);

        OrderWorker::startInBackgrond($queueKey);

        header('Location: ' . self::APP_URL . '?action=show_progress&queue=' . $queueKey);
        exit();
    }

    public function showProgress()
    {
        $queueKey = $_GET['queue'];
        $queue = new OrderQueue($queueKey);
        $initialSize = $queue->getInitialSize();
        $currentSize = $queue->getCurrentSize();
        // BEGIN Test
        if ($currentSize > 0) {
            $currentSize = $currentSize - 1;
            $queue->setCurrentSize($currentSize);
        }
        // END Test
        $progress = ceil((($initialSize - $currentSize) / $initialSize) * 100);

        include __DIR__ . '/../view/show_progress.php';
    }

}