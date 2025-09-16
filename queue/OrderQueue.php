<?php
use Predis\Client;

class OrderQueue
{
    public const REDIS_HOST = 'redis';
    public const REDIS_PORT = 6379;

    private const CURRENT_SIZE = 'current-';
    private const INITIAL_SIZE = 'initial-';
    private const SIZE_OF_QUEUE = 'size-of-queue-';

    private Client $client;
    private string $key;

    public function __construct($key)
    {
        $this->key = $key;

        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => self::REDIS_HOST,
            'port' => self::REDIS_PORT,
        ]);
    }

    public function dequeue(): ?Order
    {
        $jsonOrder = $this->client->lpop($this->key);
        $arrOrder = json_decode($jsonOrder);
        $order = Order::fromArray($arrOrder);

        $currentSize = $this->getCurrentSize();
        $this->setCurrentSize($currentSize - 1);

        return $order;
    }

    public function enqueue(Order $order): void
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

    private function getSize($type): int {
        $size = $this->client->get($type . self::SIZE_OF_QUEUE . $this->key);
        if (!empty($size)) {
            return (int) $size;
        } else {
            return 0;
        }
    }

    private function setSize($type, $size) {
        $this->client->set($type . self::SIZE_OF_QUEUE . $this->key, $size);
    }

    private function setCurrentSize($size) {
        $this->setSize(self::CURRENT_SIZE, $size);
    }

    public function setInitialSize($size) {
        $this->setSize(self::INITIAL_SIZE, $size);
    }
}