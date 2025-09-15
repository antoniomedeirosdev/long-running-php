<?php
require_once 'model/Order.php';
require_once 'controller/OrderController.php';

include 'view/header.php';

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

    default:
        # code...
        break;
}

include 'view/footer.php';