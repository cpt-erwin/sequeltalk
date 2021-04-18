<?php


namespace Sequeltak;


/**
 * Class Column
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Column
{
    /** @var string Column name */
    public string $name;

    /** @var string Smalltalk datatype of column's value */
    public string $dataType;

    /** @var ?string Object's attribute's name. */
    public ?string $variableName;

    /** @var ?string Object's value's prefix. */
    public ?string $valuePrefix;

    /**
     * Column constructor.
     * @param string $name <p>
     * Column name.
     * </p>
     * @param string $dataType <p>
     * Smalltalk datatype of column's value.<br>
     * Setting a value for this variable should be done via one of the Sequeltak\SmalltalkDataType constants.
     * </p>
     * <p>
     * For a <b>string value</b> &#9; use <b>SmalltalkDataType::STRING</b>
     * </p>
     * <p>
     * For a <b>numeric value</b> &#9; use <b>SmalltalkDataType::NUMBER</b>
     * </p>
     * <p>
     * For a <b>date value</b> &#9; use <b>SmalltalkDataType::DATE</b>
     * </p>
     * <p>
     * For a <b>collection value</b> &#9; use <b>SmalltalkDataType::SET</b>
     * </p>
     * <p>
     * For a <b>list value</b> &#9; use <b>SmalltalkDataType::LIST</b>
     * </p>
     * <p>
     * For a <b>bag value</b> &#9; use <b>SmalltalkDataType::BAG</b>
     * </p>
     * <p>
     * For a <b>object value</b> &#9; use <b>SmalltalkDataType::OBJECT</b>
     * </p>
     * @param ?string $variableName [optional] <p>
     * Strictly specifies the attribute's name.<br>
     * If no value is provided it will generate the attribute's name automatically based on column's name.
     * </p>
     */
    public function __construct(string $name, string $dataType, ?string $variableName = null, ?string $valuePrefix = null)
    {
        $this->name = $name;
        $this->dataType = $dataType;
        $this->setVariableName($variableName);
        $this->valuePrefix = $valuePrefix;
    }

    /**
     * @param string $value
     * @return string
     */
    public function getRecord(string $value): string
    {
        return "{$this->variableName}: {$this->valuePrefix}{$value}";
    }

    private function setVariableName(?string $variableName): void
    {
        if (is_null($variableName)){
            $this->variableName = lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $this->name))));
        } else {
            $this->variableName = $variableName;
        }
    }


}