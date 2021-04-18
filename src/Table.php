<?php


namespace Sequeltak;


use ErrorException;
use LogicException;
use PDO;

/**
 * Class Table
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Table
{
    /** @var string Table name. */
    public string $name;

    /** @var Column[] Table columns. */
    public array $columns;

    /** @var ?string Database that contains this table. */
    public ?string $database;

    /**
     * Table constructor.
     * @param string $name <p>
     * Name of the database table.
     * </p>
     * @param Column[] $columns <p>
     * List of table columns represented via Column objects.
     * </p>
     * @param ?string $database [optional] <p>
     * Name of the database where the desired table is.<br>
     * Do not set any value if the table is in the default .env database.
     * </p>
     */
    public function __construct(string $name, array $columns, ?string $database = null)
    {
        $this->name = $name;
        $this->setColumns($columns);
        if ($_ENV['DEBUG']) App::$app->debug('columns', $this->columns);
        $this->database = $database;
    }

    /**
     * Creates a simple record of how the Smalltalk object should be created.
     * @return string <p>
     * Smalltalk object name with attributes and their values.
     * </p>
     */
    public function getObjectSchema(): string {
        $schema = "{$this->getObjectName()}<br>";
        foreach ($this->columns as $column) {
            if ($_ENV['DEBUG']) App::$app->debug('column', $column);
            $schema .= "\t" . $column->name . ': ' . $column->dataType . '<br>';
        }
        return $schema;
    }

    /**
     * Parses SQL table rows to a Smalltalk syntax.
     * @return string<p>
     * Formatted Smalltalk friendly data from SQL table.
     * </p>
     * @throws ErrorException <p>
     * When accessing non-implemented methods.
     * </p>
     * @throws LogicException <p>
     * When $dataType is not a constant from this class.
     * </p>
     */
    public function getData(): string
    {
        $data = "";
        $query = App::$app->conn->query($this->generateSQL());
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index => $row) {
            if ($_ENV['DEBUG']) App::$app->debug('row', $row);
            $var = $this->getVariableName() . ++$index;
            $data .= "$var := $var new.<br>$var ";

            foreach ($row as $column => $value) {
                if (is_null($value)) continue;
                $data .= $this->columns[$column]->getRecord($value) . '; ';
            }

            //Remove the last character using substr
            $data = substr($data, 0, -2);
            $data .= '.<br><br>';
        }
        return $data;
    }

    /**
     * Returns the name of a SQL table with database if specified.
     * @return string <p>
     * SQL table name
     * </p>
     */
    private function getTableName(): string
    {
        if (is_null($this->database)) {
            return $this->name;
        }
        return "$this->database.$this->name";
    }

    /**
     * Generate SQL query based on specified Table attribute's values and Columns.
     * @return string <p>
     * Generated SQL query.
     * </p>
     */
    private function generateSQL(): string
    {
        $sql = "SELECT ";
        foreach ($this->columns as $column) {
            $sql .= $column->name . ', ';
        }
        $sql = substr($sql, 0, -2) . " FROM {$this->getTableName()}";

        if ($_ENV['DEBUG']) App::$app->debug('SQL', $sql);

        return $sql;
    }

    /**
     * Modifies the input array by changing the default indexes to name of columns.
     * @param Column[] $columns <p>
     * Collection of table columns
     * </p>
     */
    private function setColumns(array $columns): void
    {
        foreach ($columns as $key => $column) {
            $columns[$column->name] = $column;
            unset($columns[$key]);
        }
        $this->columns = $columns;
    }

    /**
     * Generates object name according to selected naming convention.
     * @return string <p>
     * Formatted object name based on default naming convention.
     * </p>
     */
    public function getObjectName(): string
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", $this->name)));
    }

    /**
     * Generates variable name according to selected naming convention.
     * @return string <p>
     * Formatted variable name based on default naming convention.
     * </p>
     */
    public function getVariableName(): string
    {
        return $this->name[0];
    }
}