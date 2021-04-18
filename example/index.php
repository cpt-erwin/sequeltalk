<?php

use Sequeltak\App;
use Sequeltak\Collection;
use Sequeltak\Column;
use Sequeltak\Helper;
use Sequeltak\SmalltalkDataType;
use Sequeltak\Table;

require '../vendor/autoload.php';

$app = new App();

$app->addTable(new Table('user', [
    new Column('firstName', SmalltalkDataType::STRING),
    new Column('lastName', SmalltalkDataType::STRING),
    new Column('birthdate', SmalltalkDataType::DATE)
]));

$app->addTable(new Table('billing_information', [
    new Column('country', SmalltalkDataType::STRING),
    new Column('city', SmalltalkDataType::STRING),
    new Column('street', SmalltalkDataType::STRING),
    new Column('zipCode', SmalltalkDataType::STRING),
    new Column('user_id', SmalltalkDataType::OBJECT,'user', 'u') // Foreign key
]));

$app->addTable(new Table('product', [
    new Column('name', SmalltalkDataType::STRING),
    new Column('price', SmalltalkDataType::NUMBER),
    new Column('minAge', SmalltalkDataType::NUMBER),
    new Column('onStock', SmalltalkDataType::NUMBER)
]));

$app->addTable(new Table('coupon', [
    new Column('code', SmalltalkDataType::STRING),
    new Column('expirationDate', SmalltalkDataType::DATE),
    new Column('sale', SmalltalkDataType::NUMBER)
]));

$app->addTable(new Table('order_item', [
    new Column('quantity', SmalltalkDataType::NUMBER),
    new Column('unitPrice', SmalltalkDataType::NUMBER),
    new Column('product_id', SmalltalkDataType::OBJECT, 'product', 'p') // Foreign key
]));

$app->addTable(new Table('order', [
    new Column('createdAt', SmalltalkDataType::DATE),
    new Column('state', SmalltalkDataType::NUMBER),
    new Column('user_id', SmalltalkDataType::OBJECT, 'user', 'u'), // Foreign key
    new Column('coupon_id', SmalltalkDataType::OBJECT, 'coupon', 'c') // Foreign key
]));

$app->run();

$oi2p = new Collection('OrderItems -> Products', 'p', 'oi', [
    [15, 17, 18, 19, 20, 21],
    [14, 16],
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
    [22],
    [13]
], 'orderItems');

$oi2p->printCollection();

$oi2o = new Collection('OrderItems -> Orders', 'o', 'oi', [
    [1],
    [2],
    [3],
    [4],
    [5],
    [6],
    [7],
    [8],
    [9],
    [10],
    [11],
    [12],
    [13, 14],
    [15, 16],
    [17],
    [18],
    [19],
    [20],
    [21],
    [22],
], 'orderItems');

$oi2o->printCollection();

$o2u = new Collection('Orders -> Users', 'u', 'o', [
    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
    [13, 14],
    [15, 16, 17, 18, 19],
    [20],
], 'orders');

$o2u->printCollection();

$o2u = new Collection('Invoices -> Orders', 'o', 'i', [
    0 => [1],
    1 => [2],
    2 => [3],
    3 => [4],
    4 => [5],
    5 => [6],
    6 => [7],
    7 => [8],
    8 => [9],
    9 => [10],
    11  => [11],
    12  => [12],
    13  => [13],
    14  => [14],
    15  => [15],
    16  => [16],
    17  => [17],
    18  => [18],
    19  => [19]
], 'invoice', 'state: 2');

$o2u->printCollection();

$users = new Collection('User collection', 'Users', 'u', [
    Helper::getIndexArray(5)
], null, null, false);

$users->printCollection();

$products = new Collection('Product collection', 'Products', 'p', [
    Helper::getIndexArray(5)
], null, null, false);

$products->printCollection();

$coupons = new Collection('Coupon collection', 'Coupons', 'c', [
    Helper::getIndexArray(5)
], null, null, false);

$coupons->printCollection();

$orders = new Collection('Order collection', 'Orders', 'o', [
    Helper::getIndexArray(20)
], null, null, false);

$orders->printCollection();

$orderItems = new Collection('OrderItem collection', 'OrderItems', 'oi', [
    Helper::getIndexArray(22)
], null, null, false);

$orderItems->printCollection();

$invoices = new Collection('Invoice collection', 'Invoices', 'i', [
    Helper::getIndexArray(19)
], null, null, false);

$invoices->printCollection();


