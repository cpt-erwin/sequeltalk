<?php

namespace Sequeltak;

use Dotenv\Dotenv;
use ErrorException;
use PDO;
use PDOException;

class App
{
    public function run(): void {
        // FIXME: Better way to specify .env destination than this?
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        try {
            $conn = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // Handle the PDOException
            throw new ErrorException('Couldn\'t logged into the DB: ' . $e->getMessage());
            // Change this part to suit your needs
        }

        /* specifies, if you want to treat foreign keys in table as a object references */
        $foreignKeyAsObject = false;

        /* EXAMPE */
        /*
        Let's say we have a table invoice that has a foreign key billing_information_id.

        If $foreignKeyAsObject is true it will generate the fallowing:

        i1 := Invoice new.
        i1 issueDate: '03 01 2021' asDate; dueDate: '04 01 2021' asDate; payDate: '03 02 2021' asDate; billing_information: b1.

        If $foreignKeyAsObject is false it will generate the code without billing_information_id column (same as skip):

        i1 := Invoice new.
        i1 issueDate: '03 01 2021' asDate; dueDate: '04 01 2021' asDate; payDate: '03 02 2021' asDate.

        */

        $database = "database_name";
        $table = "table_name";
        $variable = "smalltalk_variable_name";
        $object = "smalltalk_object_name";
        $skip = ['skip', 'specific', 'columns'];

        /* EXAMPLE */
        /*
            $database = "eshop";
            $table = "invoice";
            $variable = "i";
            $object = "Invoice";
            $skip = ['order_id'];
        */

        /* if true it will generate add: {$variable}{$index} from start to end */
        /* useful if you want to generate code to add specific objects to some collection */
        $manual = false;
        $start = 1;
        $end = 10;

        /* manual generator for adding items into collection */
        if ($manual) {
            $echo = "";
            for ($i = $start; $i <= $end; $i++) {
                $echo .= 'add: '. $variable . $i . "; ";
            }
            //Remove the last character using substr
            $echo = substr($echo, 0, -2);
            echo $echo . '.';
            exit;
        } else {
            $query = $conn->query("SELECT * FROM {$database}.{$table}");
            foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index => $row) {
                $index++; // Use for variable number but start with 1 instead of 0
                $smalltalk = "{$variable}{$index} := {$object} new.<br>{$variable}{$index} ";
                foreach ($row as $column => $value) {
                    /* skip columns we don't want */
                    if (in_array($column, $skip)) continue;

                    if ($foreignKeyAsObject & strpos($column, '_id') !== false) {
                        if (empty($value)) continue;
                        $column = explode('_id', $column)[0];
                        $smalltalk .= $column . ': ' . $column[0] .$value;
                    } else {
                        if (strpos($column, 'id') !== false) continue; // Skip id collumns
                        if (empty($value)) continue;

                        /* if column name is like date generate smalltalk friendly date */
                        $isDate = (strpos($column, 'date') !== false) || (strpos($column, 'Date') !== false) || (strpos($column, 'At') !== false);
                        if ($isDate) {
                            $value = explode('-', $value);
                            $value = $value[1] . ' ' . $value[2] . ' ' . $value[0];
                        }

                        if(is_numeric($value)){
                            $smalltalk .= $column . ": " . $value . "";
                        } else {
                            $smalltalk .= $column . ": '" . $value . "'";
                        }

                        if ($isDate) {
                            $smalltalk .= ' asDate';
                        }
                    }

                    $smalltalk .= "; ";
                }
                //Remove the last character using substr
                $smalltalk = substr($smalltalk, 0, -2);
                $smalltalk .= '.<br>';

                echo $smalltalk . "<br>";
            }
        }
    }
}