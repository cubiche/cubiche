<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification\Tests\Units\Evaluator;

use Cubiche\Domain\Specification\Evaluator\Evaluator;
use Cubiche\Domain\Tests\Units\TestCase;

/**
 * EvaluatorTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EvaluatorTests extends TestCase
{
    /**
     * @param $value
     *
     * @return string
     */
    public function sampleMethod($value)
    {
        return $value.'-sufix';
    }

    /*
     * Test evaluate method.
     */
    public function testEvaluate()
    {
        $this
            ->given($closure = function ($value = null) {
                return $value;
            })
            ->when($evaluator = Evaluator::fromClosure($closure))
            ->then
                ->variable($evaluator->evaluate(5))
                    ->isEqualTo(5)
            ->given($evaluator = Evaluator::fromMethod($this, 'sampleMethod'))
            ->then
                ->variable($evaluator->evaluate('text'))
                    ->isEqualTo('text-sufix')
            ->given($evaluator = Evaluator::fromFunction('array_flip'))
            ->then
                ->array($evaluator->evaluate(array('oranges', 'apples', 'strawberries')))
                    ->isEqualTo(array('oranges' => 0, 'apples' => 1, 'strawberries' => 2))
        ;
    }
}
