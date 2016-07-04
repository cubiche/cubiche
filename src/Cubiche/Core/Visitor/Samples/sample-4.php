<?php

require 'vendor/autoload.php';

use Cubiche\Core\Visitor\Tests\Fixtures\Calculator;
use Cubiche\Core\Visitor\Tests\Fixtures\Evaluator;
use Cubiche\Core\Visitor\Tests\Fixtures\Mult;
use Cubiche\Core\Visitor\Tests\Fixtures\SmartExpressionToStringConverter;
use Cubiche\Core\Visitor\Tests\Fixtures\Sum;
use Cubiche\Core\Visitor\Tests\Fixtures\Value;
use Cubiche\Core\Visitor\Tests\Fixtures\Variable;

$expression = new Mult(
    new Variable('x'),
    new Sum(
        new Value(5),
        new Mult(
            new Variable('y'),
            new Value(2)
        )
    )
);

$variables = array('x' => 10, 'y' => -7);
$evaluator = new Evaluator(new Calculator(), $variables);
$converter = new SmartExpressionToStringConverter();

echo $expression->accept($converter).' = '.$expression->accept($evaluator).PHP_EOL;
