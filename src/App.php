<?php

namespace Sequeltak;

use Dotenv\Dotenv;
use LogicException;
use PDO;
use PDOException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

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
     * @throws PDOException
     */
    public function __construct()
    {
        self::$app = $this;

        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();

        // FIXME: Better way to specify .env destination than this?
        // Initialize .env configuration
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Set $_ENV['DEBUG'] value as boolean instead of string
        $_ENV['DEBUG'] = filter_var($_ENV['DEBUG'], FILTER_VALIDATE_BOOLEAN);

        $this->conn = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Executes the application.
     * @throws LogicException <p>
     * When $dataType is not a constant from this class.
     * </p>
     */
    public function run(): void
    {
        $stringBuilder = new StringBuilder();
        foreach ($this->tables as $table) {
            echo $stringBuilder
                ->appendLine("\"-------------- {$table->getObjectName()} object schema --------------\"")
                ->append("<pre>")
                ->append($table->getObjectSchema())
                ->append("</pre>")
                ->appendLine("\"-------------- {$table->getObjectName()} records --------------\"")
                ->append("<pre>")
                ->append($table->getData())
                ->append("</pre>")
                ->build();
        }
    }

    public function addTable(Table $table): void
    {
        $this->tables[] = $table;
    }
}