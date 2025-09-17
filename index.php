<?php
require_once 'controller/OrderController.php';
require_once 'model/Order.php';
require_once 'queue/OrderQueue.php';
require_once 'queue/OrderWorker.php';
require __DIR__ . '/vendor/autoload.php';

session_start();

if (!isset($_GET['action'])) {
    $_GET['action'] = 'list_orders';
}

switch ($_GET['action']) {
    case 'generate_orders':
        OrderController::getInstance()->generateOrders();
        break;

    case 'list_orders':
        OrderController::getInstance()->listOrders();
        break;

    case 'process_orders':
        OrderController::getInstance()->processOrders();
        break;

    case 'show_progress':
        OrderController::getInstance()->showProgress();
        break;

    default:
        # code...
        break;
}
