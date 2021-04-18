<?php


namespace Sequeltak;


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
        $this->columns = $columns;
        $this->database = $database;
    }

    public function getObjectSchema(): string {
        return '';
    }
}