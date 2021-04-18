<?php

use Sequeltak\App;
use Sequeltak\Column;
use Sequeltak\SmalltalkDataType;
use Sequeltak\Table;

require '../vendor/autoload.php';

$app = new App();

$app->addTable(new Table('billing_information', [
    new Column('country', SmalltalkDataType::STRING),
    new Column('city', SmalltalkDataType::STRING),
    new Column('street', SmalltalkDataType::STRING),
    new Column('zipCode', SmalltalkDataType::STRING),
    new Column('user_id', SmalltalkDataType::OBJECT,'user', 'u') // Foreign key
]));

$app->addTable(new Table('user', [
    new Column('firstName', SmalltalkDataType::STRING),
    new Column('lastName', SmalltalkDataType::STRING),
    new Column('birthdate', SmalltalkDataType::DATE)
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
    new Column('user_id', SmalltalkDataType::OBJECT, 'user', 'u') // Foreign key
]));

$app->addTable(new Table('invoice', [
    new Column('issueDate', SmalltalkDataType::DATE),
    new Column('dueDate', SmalltalkDataType::DATE),
    new Column('payDate', SmalltalkDataType::DATE)
]));

$app->run();