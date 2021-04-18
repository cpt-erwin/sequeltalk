<?php

use Sequeltak\App;
use Sequeltak\Column;
use Sequeltak\SmalltalkDataType;
use Sequeltak\Table;

require '../vendor/autoload.php';

$app = new App();

// Record placeholder
$app->addTable(new Table('table_name', [
    new Column('column_name_1', SmalltalkDataType::STRING),
    new Column('column_name_2', SmalltalkDataType::NUMBER),
    new Column('column_name_3', SmalltalkDataType::DATE),
    new Column('column_name_4', SmalltalkDataType::OBJECT,'myColName', 'mcn') // Foreign key
]));
// ^ Replace with your tables

$app->run();