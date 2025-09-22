<?php
use Predis\Client;

class OrderWorker
{
    public const JSON_SERVER_URL = 'http://json-server/orders';

    private OrderQueue $queue;

    public function __construct($key)
    {
        $this->queue = new OrderQueue($key);
    }

    public function processOrders()
    {
        $initialSize = $this->queue->getInitialSize();
        for ($currentOrder = 1; $currentOrder <= $initialSize; $currentOrder++) { 
            $order = $this->queue->dequeue();
            $this->remoteProcess($order);
            $this->updateOrder($order);
        }
    }

    private function remoteProcess(Order $order)
    {
        // Sleep some seconds (decided at random) to simulate talking to another system
        $randomNumber = mt_rand(0, 2);
        sleep($randomNumber);

        // Also define a random new status
        $order->setStatus(Order::randomStatus());
    }

    public static function startInBackgrond($key) {
        // https://stackoverflow.com/a/23572776/1657502
        $command = 'nohup nice php ' . __DIR__ . '/background_script.php "' . $key . '" > /dev/null 2>&1 & echo $!';
        $pid = shell_exec($command);
    }

    private function updateOrder(Order $order)
    {
        $data = $order->toArray();
        $jsonData = json_encode($data);

        $curl = curl_init(self::JSON_SERVER_URL . '/' . $order->getId());

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