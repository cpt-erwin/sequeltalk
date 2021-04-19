<?php


namespace Sequeltak;


/**
 * Class Debugger
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Debugger
{
    /**
     * Simple tool for debugging observed variables.
     * @param string $tag<p>
     * Keyword for the debug message.
     * </p>
     * @param string $content<p>
     * Content of the observed value.
     * </p>
     */
    public static function debug(string $tag, string $content): void
    {
        echo "<pre style='white-space: pre-wrap; background-color: #ebebeb; padding: .1rem 0 1rem 0;'>";
        echo "<h3 style='text-align: center;'>$tag</h3><hr><div style='padding: 0 1rem;'>";
        echo $content;
        echo "</div></pre>";
    }

    /**
     * Simple tool for debugging observed variables.
     * @param string $tag<p>
     * Keyword for the debug message.
     * </p>
     * @param array $content<p>
     * Content of the observed value.
     * </p>
     */
    public static function debugArray(string $tag, array $content): void
    {
        self::debug($tag, json_encode($content));
    }

    /**
     * Simple tool for debugging observed variables.
     * @param string $tag<p>
     * Keyword for the debug message.
     * </p>
     * @param object $content<p>
     * Content of the observed value.
     * </p>
     */
    public static function debugObject(string $tag, object $content): void
    {
        self::debug($tag, json_encode($content));
    }
}