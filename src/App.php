<?php

namespace Sequeltak;

use Dotenv\Dotenv;
use ErrorException;
use LogicException;
use PDO;
use PDOException;

/**
 * Class App
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class App
{
    /** @var App Store an instance of itself in itself to make it globally accessible. */
    public static App $app;

    /** @var PDO PDO connector. */
    public PDO $conn;

    /** @var Table[] Collection of SQL tables represented via Table objects. */
    private array $tables = [];

    /**
     * App constructor.
     * @throws ErrorException
     */
    public function __construct()
    {
        self::$app = $this;

        // FIXME: Better way to specify .env destination than this?
        // Initialize .env configuration
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Set $_ENV['DEBUG'] value as boolean instead of string
        $_ENV['DEBUG'] = filter_var($_ENV['DEBUG'], FILTER_VALIDATE_BOOLEAN);

        try {
            $this->conn = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle the PDOException
            throw new ErrorException("Couldn't logged into the DB: " . $e->getMessage());
            // Change this part to suit your needs
        }
    }

    /**
     * Executes the application.
     * @throws ErrorException <p>
     * When accessing non-implemented methods.
     * </p>
     * @throws LogicException <p>
     * When $dataType is not a constant from this class.
     * </p>
     */
    public function run(): void
    {
        foreach ($this->tables as $table) {
            echo "\"-------------- {$table->getObjectName()} object schema --------------\"<br>";
            echo "<pre>" . $table->getObjectSchema() . "</pre>";

            echo "\"-------------- {$table->getObjectName()} records --------------\"<br><br>";
            echo $table->getData() . "<br><br>";
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
     * @return string<p>
     * New collection object with entries.
     * </p>
     */
    function collectionGenerator(string $collection, string $variable, int $iterations, int $start = 1): string
    {
        // Create new collection
        $record = "$collection := new Set.<br>$collection ";

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

    /**
     * Simple tool for debugging observed variables.
     * @param string $tag<p>
     * Keyword for the debug message.
     * </p>
     * @param mixed $content<p>
     * Content of the observed value.
     * </p>
     */
    public function debug(string $tag, $content): void
    {
        echo "<pre style='white-space: pre-wrap; background-color: #ebebeb; padding: .1rem 0 1rem 0;'>";
        echo "<h3 style='text-align: center;'>$tag</h3><hr><div style='padding: 0 1rem;'>";
        print_r($content);
        echo "</div></pre>";
    }

    public function addTable(Table $table): void
    {
        $this->tables[] = $table;
    }
}