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
     * @param string|null $heading
     * @param string $collectionVariable
     * @param string $recordVariable
     * @param array $recordsCollection
     * @param string|null $attribute
     * @param string|null $appendCode
     * @param bool $collectionExists
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
        $stringBuilder = new StringBuilder();
        if (!is_null($this->heading)) {
            $stringBuilder
                ->appendLine("\"-------------- $this->heading --------------\"");
        }

        $stringBuilder->append('<pre>');

        foreach ($this->recordsCollection as $index => $records) {
            $number = sizeof($this->recordsCollection) > 1 ? ($index + 1) : '';

            if (!$this->collectionExists) {
                $stringBuilder
                    ->append($this->collectionVariable)
                    ->append($number)
                    ->appendLine(' := Set new.');
            }

            $stringBuilder
                ->append($this->collectionVariable)
                ->append($number)
                ->appendSpace()
                ->append(is_null($this->attribute) ? '' : $this->attribute)
                ->appendSpace();

            foreach ($records as $key => $record) {
                $stringBuilder
                    ->append('add: ')
                    ->append($this->recordVariable)
                    ->append($record);

                if ($key === array_key_last($records)) continue;

                $stringBuilder->append('; ');
            }

            if (!is_null($this->appendCode)) {
                $stringBuilder
                    ->append('; ')
                    ->append($this->appendCode);
            }

            $stringBuilder->appendLine('.');
        }

        echo $stringBuilder
            ->append('</pre>')
            ->build();
    }
}