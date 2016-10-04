<?php

use Cubiche\Core\Enumerable\Enumerable;
use Cubiche\Core\Enumerable\Tests\Fixtures\Person;
use Cubiche\Core\Enumerable\Tests\Fixtures\Value;

function sequence($count, $min = 0, $max = 10, $mixed = false)
{
    $array = array();
    while (--$count >= 0) {
        $value = \rand($min, $max);
        $array[] = $mixed && \rand(0, 1) === 0 ? $value : new Value($value);
    }

    return $array;
}

function persons($count)
{
    $array = array();
    $i = 0;
    while ($i < $count) {
        $array[] = new Person('Person '.$i, \rand(20, 25), \rand(170, 175), \rand(65, 70));
        ++$i;
    }

    return $array;
}

function sequenceToString($sequence, $newLine = false)
{
    return \implode(', '.($newLine ? PHP_EOL : ''), Enumerable::from($sequence)->toArray());
}

function boolToString($boolean)
{
    return $boolean ? 'yes' : 'no';
}
