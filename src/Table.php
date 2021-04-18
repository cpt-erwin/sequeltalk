<?php


namespace Sequeltak;


use PDO;

/**
 * Class Table
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Table
{
    /** @var string Table name */
    public string $name;

    /** @var Column[] Table columns */
    public array $columns;

    /** @var ?string Database that contains this table */
    public ?string $database;

    /**
     * Table constructor.
     * @param string $name
     * @param Column[] $columns
     * @param ?string $database
     */
    public function __construct(string $name, array $columns, ?string $database = null)
    {
        $this->name = $name;
        $this->setColumns($columns);
        if ($_ENV['DEBUG']) App::$app->debug('columns', $this->columns);
        $this->database = $database;
    }

    public function getObjectSchema(): string {
        $schema = "{$this->getObjectName()}<br>";
        foreach ($this->columns as $column) {
            if ($_ENV['DEBUG']) App::$app->debug('column', $column);
            $schema .= "\t" . $column->name . ': ' . $column->dataType . '<br>';
        }
        return $schema;
    }

    public function getData()
    {
        $data = "";
        $query = App::$app->conn->query($this->generateSQL());
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index => $row) {
            if ($_ENV['DEBUG']) App::$app->debug('row', $row);
            $var = $this->getVariableName() . ++$index;
            $data .= "{$var} := {$var} new.<br>{$var} ";

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
     * @return string SQL table name
     */
    private function getTableName(): string
    {
        if (is_null($this->database)) {
            return $this->name;
        }
        return "{$this->database}.{$this->name}";
    }

    /**
     * Generate SQL query based on specified Table attribute's values and Columns.
     * @return string Generated SQL query
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
     * @param Column[] $columns Collection of table columns
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
     * @return string
     */
    public function getObjectName(): string
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", $this->name)));
    }

    /**
     * Generates variable name according to selected naming convention.
     * @return string
     */
    public function getVariableName(): string
    {
        return $this->name[0];
    }
}