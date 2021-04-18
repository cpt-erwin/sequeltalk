<?php


namespace Sequeltak;


class Column
{
    /** @var string Column name */
    public string $name;

    /** @var string Smalltalk datatype of column's value */
    public string $dataType;

    /**
     * Column constructor.
     * @param string $name
     * @param string $dataType
     */
    public function __construct(string $name, string $dataType)
    {
        $this->name = $name;
        $this->dataType = $dataType;
    }


}