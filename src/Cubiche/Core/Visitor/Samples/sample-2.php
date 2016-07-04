<?php

require 'vendor/autoload.php';

use Cubiche\Core\Visitor\Tests\Fixtures\Calculator;
use Cubiche\Core\Visitor\Tests\Fixtures\Mult;
use Cubiche\Core\Visitor\Tests\Fixtures\Sum;
use Cubiche\Core\Visitor\Tests\Fixtures\Value;
use Cubiche\Core\Visitor\Tests\Fixtures\ExpressionToStringConverter;

$expression = new Sum(
    new Value(5),
    new Mult(
        new Value(3),
        new Value(2)
    )
);

$calculator = new Calculator();
$result = $expression->accept($calculator);

$converter = new ExpressionToStringConverter();
echo $expression->accept($converter).' = '.$result.PHP_EOL;
