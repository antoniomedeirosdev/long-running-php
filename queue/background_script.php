<?php
require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../queue/OrderQueue.php';
require_once __DIR__ . '/../queue/OrderWorker.php';
require __DIR__ . '/../vendor/autoload.php';

$queueKey = $argv[1];
$worker = new OrderWorker($queueKey);
$worker->processOrders();