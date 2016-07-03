<?php

require 'vendor/autoload.php';

use Cubiche\Core\Visitor\Tests\Fixtures\Calculator;
use Cubiche\Core\Visitor\Tests\Fixtures\Mult;
use Cubiche\Core\Visitor\Tests\Fixtures\SmartExpressionToStringConverter;
use Cubiche\Core\Visitor\Tests\Fixtures\Sum;
use Cubiche\Core\Visitor\Tests\Fixtures\Value;

$expression1 = new Sum(
    new Value(5),
    new Mult(
        new Value(3),
        new Value(2)
    )
);
$expression2 = new Mult(
    new Value(4),
    $expression1
);

$calculator = new Calculator();
$converter = new SmartExpressionToStringConverter();

echo $expression1->accept($converter).' = '.$expression1->accept($calculator).PHP_EOL;
echo $expression2->accept($converter).' = '.$expression2->accept($calculator).PHP_EOL;
