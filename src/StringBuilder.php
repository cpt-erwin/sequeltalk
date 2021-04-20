<?php


namespace Sequeltak;


/**
 * Class StringBuilder
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class StringBuilder
{
    /** @var string Stores content for build. */
    private string $content = '';

    /** @var string Separator in case of joining multiple strings via $this->appendMultiple. */
    private string $separator;

    /**
     * StringBuilder constructor.
     * @param string $separator [optional] <p>
     * Separator in case of joining multiple strings via $this->appendMultiple.
     * </p>
     */
    public function __construct(string $separator = '')
    {
        $this->separator = $separator;
    }


    /**
     * Adds provided string into a builder content.
     * @param string $string <p>
     * Partial content.
     * </p>
     */
    public function append(string $string): void
    {
        $this->content .= $string;
    }

    /**
     * Adds provided string into a builder content with new line at the end.
     * @param string $string <p>
     * Partial content.
     * </p>
     */
    public function appendLine(string $string): void
    {
        $this->appendMultiple($string, '<br>');
    }


    /**
     * Adds provided strings into a builder content.
     * @param string ...$strings <p>
     * Multiple strings with partial content.
     * </p>
     */
    public function appendMultiple(string ...$strings): void
    {
        $this->content .= implode($this->separator, $strings);
    }

    /**
     * Returns the built content as a string.
     * @return string <p>
     * Builder content.
     * </p>
     */
    public function build(): string
    {
        return $this->content;
    }
}