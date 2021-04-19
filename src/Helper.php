<?php


namespace Sequeltak;


/**
 * Class Helper
 *
 * @author Michal Tuček <michaltk1@gmail.com>
 * @package Sequeltak
 */
class Helper
{
    static function getIndexArray(int $size): array {
        $arr = [];
        for ($i = 1; $i <= $size; $i++) {
            $arr[] = $i;
        }
        return $arr;
    }
}