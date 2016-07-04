# Cubiche/Visitor
[![Build Status](https://travis-ci.org/cubiche/visitor.svg?branch=master)](https://travis-ci.org/cubiche/visitor) [![Coverage Status](https://coveralls.io/repos/github/cubiche/visitor/badge.svg?branch=master)](https://coveralls.io/github/cubiche/visitor?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cubiche/visitor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cubiche/visitor/?branch=master)

A [visitor pattern](https://en.wikipedia.org/wiki/Visitor_pattern) implementation in PHP,  using a [Dynamic dispatch](https://en.wikipedia.org/wiki/Dynamic_dispatch) 
mechanism.

## Installation

Via [Composer](http://getcomposer.org/)

```bash
$ composer require cubiche/visitor:dev-master
```

## Basic usage

Arithmetic expressions calculator
```php

use Cubiche\Core\Visitor\Visitee;
use Cubiche\Core\Visitor\Visitor;

abstract class Expression extends Visitee {}

abstract class Operator extends Expression
{
    protected $operator;
    protected $firstOperand;
    protected $secondOperand;
    
    public function __construct($operator, Expression $firstOperand, Expression $secondOperand)
    {
        $this->operator = $operator;
        $this->firstOperand = $firstOperand;
        $this->secondOperand = $secondOperand;
    }
    
    public function operator()
    {
        return $this->operator;
    }
    
    public function firstOperand()
    {
        return $this->firstOperand;
    }
    
    public function secondOperand()
    {
        return $this->secondOperand;
    }
}

class Sum extends Operator
{
    public function __construct(Expression $firstOperand, Expression $secondOperand)
    {
        parent::__construct('+', $firstOperand, $secondOperand);
    }
}

class Mult extends Operator
{
    public function __construct(Expression $firstOperand, Expression $secondOperand)
    {
      parent::__construct('*', $firstOperand, $secondOperand);
    }
}

class Value extends Expression
{
    protected $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function value()
    {
        return $this->value;
    }
}

class Variable extends Expression
{
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }
    
    public function name()
    {
        return $this->name;
    }
}

class Calculator extends Visitor
{
    public function visitSum(Sum $sum)
    {
        return $sum->firstOperand()->accept($this) + $sum->secondOperand()->accept($this);
    }
    
    public function visitMult(Mult $mult)
    {
        return $mult->firstOperand()->accept($this) * $mult->secondOperand()->accept($this);
    }
    
    public function visitValue(Value $value)
    {
        return $value->value();
    }
}

$expression = new Sum(
    new Value(5),
    new Mult(
        new Value(3),
        new Value(2)
    )
);

$calculator = new Calculator();
$result = $expression->accept($calculator);
```

## Visit parent class

```php
use Cubiche\Core\Visitor\Visitor;

class ExpressionToStringConverter extends Visitor
{
    public function visitOperator(Operator $op)
    {
        return '('.$op->firstOperand()->accept($this).$op->operator().$op->secondOperand()->accept($this).')';
    }
    
    public function visitValue(Value $value)
    {
        return (string) $value->value();
    }
    
    public function visitVariable(Variable $variable)
    {
        return $variable->name();
    }
}

$expression = new Sum(
    new Value(5),
    new Mult(
        new Value(3),
        new Value(2)
    )
);

$converter = new ExpressionToStringConverter();
echo $expression->accept($converter);
```
Note that the ```visitOperator``` method is invoked when an operator(```Sum``` or ```Mult```) is visited.

## Passing parameters to visit methods

```php
class SmartExpressionToStringConverter extends ExpressionToStringConverter
{
    public function visitOperator(Operator $op, $parentOperator = null)
    {
        $currentOperator = $op->operator();
        $expression = $op->firstOperand()->accept($this, $currentOperator).
            $currentOperator.$op->secondOperand()->accept($this, $currentOperator);

        return $this->requireParentheses($currentOperator, $parentOperator) ? '('.$expression.')' : $expression;
    }

    protected function requireParentheses($currentOperator, $parentOperator)
    {
        return $currentOperator === '+' && $parentOperator === '*';
    }
}
$expression = new Sum(
    new Value(5),
    new Mult(
        new Value(3),
        new Value(2)
    )
);

$converter = new SmartExpressionToStringConverter();
echo $expression->accept($converter);
```

## Extending another visitor without inheritance

```php
use Cubiche\Core\Visitor\LinkedVisitor;

class Evaluator extends LinkedVisitor
{
    protected $variables;
    
    public function __construct(Calculator $calculator, $variables = array())
    {
        parent::__construct($calculator);
        $this->variables = $variables;
    }
    
    public function visitVariable(Variable $variable)
    {
        if (isset($this->variables[$variable->name()])) {
            return $this->variables[$variable->name()];
        }
        throw new \Exception(\sprintf("Unknown variable '%s'", $variable->name()));
    }
}

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
```
Note that the ```Evaluator``` can only visit the ```Variable``` instances and delegate in ```Calculator``` the others expressions.
