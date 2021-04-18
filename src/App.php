<?php

namespace Sequeltak;

use Dotenv\Dotenv;
use ErrorException;
use PDO;
use PDOException;

class App
{
    /** @var PDO PDO connector */
    private PDO $conn;

    /** @var Table[]  */
    private array $tables;

    /**
     * App constructor.
     * @param Table[] $tables
     * @throws ErrorException
     */
    public function __construct(array $tables)
    {
        // FIXME: Better way to specify .env destination than this?
        // Initialize .env configuration
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        try {
            $this->conn = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle the PDOException
            throw new ErrorException('Couldn\'t logged into the DB: ' . $e->getMessage());
            // Change this part to suit your needs
        }

        $this->tables = $tables;
    }

    public function run(): void
    {
        /* specifies, if you want to treat foreign keys in table as a object references */
        $foreignKeyAsObject = false;

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

        $query = $this->conn->query("SELECT * FROM {$database}.{$table}");
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index => $row) {
            $index++; // Use for variable number but start with 1 instead of 0
            $smalltalk = "{$variable}{$index} := {$object} new.<br>{$variable}{$index} ";
            foreach ($row as $column => $value) {
                /* skip columns we don't want */
                if (in_array($column, $skip)) continue;

                if ($foreignKeyAsObject & strpos($column, '_id') !== false) {
                    if (empty($value)) continue;
                    $column = explode('_id', $column)[0];
                    $smalltalk .= $column . ': ' . $column[0] . $value;
                } else {
                    if (strpos($column, 'id') !== false) continue; // Skip id collumns
                    if (empty($value)) continue;

                    /* if column name is like date generate smalltalk friendly date */
                    $isDate = (strpos($column, 'date') !== false) || (strpos($column, 'Date') !== false) || (strpos($column, 'At') !== false);
                    if ($isDate) {
                        $value = explode('-', $value);
                        $value = $value[1] . ' ' . $value[2] . ' ' . $value[0];
                    }

                    if (is_numeric($value)) {
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

    /**
     * Manually generates collection initialization with records added into itself.
     * @param string $collection <p>
     * Name for the new collection variable.
     * </p>
     * @param string $variable <p>
     * Name variable being added to the collection.
     * </p>
     * @param int $iterations <p>
     * How many iterations should do loop go trough.
     * </p>
     * @param int $start [optional] <p>
     * Additionally, you can change the starting number of the first entry.
     * </p>
     * @return string
     */
    function collectionGenerator(string $collection, string $variable, int $iterations, int $start = 1): string
    {
        // Create new collection
        $record = "{$collection} := new Set.<br>{$collection} ";

        // Treat $start as a offset in order to keep the specified number of iterations
        $end = ($iterations + ($start - 1));

        // Add records to collection
        for ($i = $start; $i <= $end; $i++) {
            $record .= 'add: ' . $variable . $i . "; ";
        }

        // Remove the last character using substr
        $record = substr($record, 0, -2);

        // Add . at the end
        $record .= '.';

        return $record;
    }
}