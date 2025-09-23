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

    public function getProgress() {
        $queueKey = $_GET['queue'];
        $queue = new OrderQueue($queueKey);
        $progress = $queue->getProgress();
        echo $progress;
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

        $arrOrder = [];
        foreach ($arrId as $id) {
            $order = new Order($id);
            $arrOrder[] = $order;
        }

        $queue = new OrderQueue(null, $arrOrder);
        $queueKey = $queue->getKey();

        // To debug the background script, comment the next line
        OrderWorker::startInBackgrond($queueKey);

        header('Location: ' . self::APP_URL . '?action=show_progress&queue=' . $queueKey);
        exit();
    }

    public function showProgress()
    {
        $queueKey = $_GET['queue'];

        // BEGIN Debugging the background script
        //$argv[1] = $queueKey;
        //include __DIR__ . '/../queue/background_script.php';
        // END Debugging the background script

        include __DIR__ . '/../view/show_progress.php';
    }

}