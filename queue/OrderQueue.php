<?php
use Predis\Client;

class OrderQueue
{
    public const REDIS_HOST = 'redis';
    public const REDIS_PORT = 6379;

    private const CURRENT_SIZE = 'current-';
    private const INITIAL_SIZE = 'initial-';
    private const RESULT_OF_QUEUE = 'result-of-queue-';
    private const SIZE_OF_QUEUE = 'size-of-queue-';

    private Client $client;
    private string $key;

    public function __construct(
        $key = null,
        $arrOrder = []
    ) {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => self::REDIS_HOST,
            'port' => self::REDIS_PORT,
        ]);

        if (empty($key)) {
            $this->key = Order::uuidgen();
            foreach ($arrOrder as $order) {
                $this->enqueue($order);
            }
            $size = count($arrOrder);
            $this->setInitialSize($size);
            $this->setCurrentSize($size);
        } else {
            $this->key = $key;
        }
    }

    public function dequeue(): ?Order
    {
        $jsonOrder = $this->client->lpop($this->key);
        $arrOrder = json_decode($jsonOrder, true);
        $order = Order::fromArray($arrOrder);

        $currentSize = $this->getCurrentSize();
        $this->setCurrentSize($currentSize - 1);

        return $order;
    }

    private function enqueue(Order $order): void
    {
        $arrOrder = $order->toArray();
        $jsonOrder = json_encode($arrOrder);
        $this->client->rpush($this->key, $jsonOrder);
    }

    public function getCurrentSize(): int {
        return $this->getSize(self::CURRENT_SIZE);
    }

    public function getInitialSize(): int {
        return $this->getSize(self::INITIAL_SIZE);
    }

    public function getKey(): string {
        return $this->key;
    }

    public function getProgress(): int {
        $initialSize = $this->getInitialSize();
        $currentSize = $this->getCurrentSize();
        $progress = ceil((($initialSize - $currentSize) / $initialSize) * 100);
        return $progress;
    }

    public function getResult() {
        $jsonResult = $this->getResultJson();
        $arrResult = json_decode($jsonResult, true);
        return $arrResult;
    }

    private function getResultJson(): string|null {
        $jsonResult = $this->client->get(self::RESULT_OF_QUEUE . $this->key);
        return $jsonResult;
    }

    private function getSize($type): int {
        $size = $this->client->get($type . self::SIZE_OF_QUEUE . $this->key);
        if (!empty($size)) {
            return (int) $size;
        } else {
            return 0;
        }
    }

    public function isFinished() {
        $jsonResult = $this->getResultJson();
        return (!empty($jsonResult));
    }

    public function setResult($arrOrder) {
        $arrResult = [];
        foreach ($arrOrder as $order) {
            $arrResult[] = [
                'id' => $order->getId(),
                'status' => $order->getStatusAsString()
            ];
        }
        $jsonResult = json_encode($arrResult);
        $this->client->set(self::RESULT_OF_QUEUE . $this->key, $jsonResult);
    }

    private function setSize($type, $size) {
        $this->client->set($type . self::SIZE_OF_QUEUE . $this->key, $size);
    }

    private function setCurrentSize($size) {
        $this->setSize(self::CURRENT_SIZE, $size);
    }

    private function setInitialSize($size) {
        $this->setSize(self::INITIAL_SIZE, $size);
    }
}