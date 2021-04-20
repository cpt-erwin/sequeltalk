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

    /**
     * Adds provided string into a builder content.
     * @param string $string <p>
     * Partial content.
     * </p>
     */
    public function append(string $string): StringBuilder
    {
        $this->content .= $string;
        return $this;
    }

    /**
     * Adds provided string into a builder content with new line at the end.
     * @param string $string <p>
     * Partial content.
     * </p>
     */
    public function appendLine(string $string): StringBuilder
    {
        $this->content .= "$string <br>";
        return $this;
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