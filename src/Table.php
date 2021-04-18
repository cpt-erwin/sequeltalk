<?php


namespace Sequeltak;


class Table
{
    /** @var string Table name */
    public string $name;

    /** @var Column[] Table columns */
    public array $columns;

    /**
     * Table constructor.
     * @param string $name
     * @param Column[] $columns
     */
    public function __construct(string $name, array $columns)
    {
        $this->name = $name;
        $this->columns = $columns;
    }

    public function getObjectSchema(): string {
        // TODO: Implement me!
        return '';
    }
}