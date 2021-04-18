<?php


namespace Sequeltak;


/**
 * Class Collection
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Collection
{
    private ?string $heading;

    private string $collectionVariable;

    private string $recordVariable;

    private array $recordsCollection;

    private ?string $attribute;

    private ?string $appendCode;

    private bool $collectionExists;

    /**
     * Collection constructor.
     * @param ?string $heading
     * @param string $collectionVariable
     * @param string $recordVariable
     * @param array $records
     */
    public function __construct(
        ?string $heading,
        string $collectionVariable,
        string $recordVariable,
        array $recordsCollection,
        ?string $attribute = null,
        ?string $appendCode = null,
        bool $collectionExists = true
    )
    {
        $this->heading = $heading;
        $this->collectionVariable = $collectionVariable;
        $this->recordVariable = $recordVariable;
        $this->recordsCollection = $recordsCollection;
        $this->attribute = $attribute;
        $this->appendCode = $appendCode;
        $this->collectionExists = $collectionExists;
    }

    function printCollection(): void
    {
        if (!is_null($this->heading))
            echo "\"-------------- {$this->heading} --------------\"<br><br>";

        foreach ($this->recordsCollection as $index => $records) {
            $number = sizeof($this->recordsCollection) > 1 ? ++$index : '';

            $collection = '';
            if (!$this->collectionExists) {
                $collection .= $this->collectionVariable . $number . ' := Set new.<br>';
            }

            $collection .= $this->collectionVariable . $number . ' ' . (is_null($this->attribute) ? '' : $this->attribute . ' ');
            foreach ($records as $record) {
                $collection .= 'add: ' . $this->recordVariable . $record . '; ';
            }
            //Remove the last character using substr
            $collection = substr($collection, 0, -2);

            if (!is_null($this->appendCode)) {
                $collection .= '; ' . $this->appendCode;
            }

            $collection .= '.<br>';
            echo $collection;
        }
        echo '<br><br>';
    }
}