<?php


namespace Sequeltak;


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
        if ($_ENV['DEBUG']) Debugger::debugArray('columns', $this->columns);
        $this->database = $database;
    }

    /**
     * Creates a simple record of how the Smalltalk object should be created.
     * @return string <p>
     * Smalltalk object name with attributes and their values.
     * </p>
     */
    public function getObjectSchema(): string {

        $schema = new StringBuilder();

        $schema
            ->append($this->getObjectName())
            ->append("<br>");

        foreach ($this->columns as $column) {
            if ($_ENV['DEBUG']) Debugger::debugObject('column', $column);
            $schema
                ->append("\t")
                ->append($column->name)
                ->append(': ')
                ->appendLine($column->dataType);
        }
        return $schema->build();
    }

    /**
     * Parses SQL table rows to a Smalltalk syntax.
     * @return string<p>
     * Formatted Smalltalk friendly data from SQL table.
     * </p>
     * @throws LogicException <p>
     * When $dataType is not a constant from this class.
     * </p>
     */
    public function getData(): string
    {
        $data = new StringBuilder();
        $query = App::$app->conn->query($this->generateSQL());
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index => $row) {
            if ($_ENV['DEBUG']) Debugger::debugArray('row', $row);
            $var = $this->getVariableName() . ($index + 1);

            $data
                ->appendLine("$var := $var new.")
                ->append("$var ");

            foreach ($row as $column => $value) {
                if (is_null($value)) continue;

                $data
                    ->appendLine($this->columns[$column]->getRecord($value));

                if ($column === array_key_last($row)) continue;

                $data->append('; ');
            }
            $data->appendLine('.');
        }
        return $data->build();
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
        $sql = new StringBuilder();;
        $sql->append("SELECT ");

        foreach ($this->columns as $index => $column) {
            $sql
                ->append('`')
                ->append($column->name)
                ->append('`');

            if ($index === array_key_last($this->columns)) continue;

            $sql->append(', ');
        }

        $sql
            ->append(" FROM `")
            ->append($this->getTableName())
            ->append("`");

        if ($_ENV['DEBUG']) Debugger::debug('SQL', $sql->build());

        return $sql->build();
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
        $expr = '/(?<=\s|^)[A-Z]/';
        preg_match_all($expr, ucwords(str_replace("_", " ", $this->name)), $matches);
        return strtolower(implode('', $matches[0]));
    }
}