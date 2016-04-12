<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector\Tests\Units;

/**
 * MethodTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodTests extends FieldTestCase
{
    /**
     * @return bool
     */
    public function methodTrue()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function methodFalse()
    {
        return false;
    }

    /**
     * @param number $arg1
     * @param number $arg2
     *
     * @return number
     */
    public function methodWithArgs($arg1, $arg2)
    {
        return $arg1 + $arg2;
    }

    protected function privateMethod()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Visitor\Tests\Units\VisiteeInterfaceTestCase::shouldVisitMethod()
     */
    protected function shouldVisitMethod()
    {
        return 'visitMethod';
    }

    /**
     * Test apply.
     */
    public function testApply()
    {
        $this
            ->given($method = $this->newTestedInstance('name'))
            ->then()
                ->string($method->apply($method))
                    ->isEqualTo('name')
        ;

        $this
            ->given($method = $this->newTestedInstance('methodWithArgs'))
            ->then()
                ->integer($method->with(1, 2)->apply($this))
                    ->isEqualTo(3)
        ;

        $this
            ->given($method = $this->newTestedInstance('foo'))
            ->then()
                ->exception(function () use ($method) {
                    $method->apply(null);
                })->isInstanceOf(\RuntimeException::class)
                ->exception(function () use ($method) {
                    $method->apply($this);
                })->isInstanceOf(\RuntimeException::class)
        ;

        $this
            ->given($method = $this->newTestedInstance('privateMethod'))
            ->then()
                ->exception(function () use ($method) {
                    $method->apply($this);
                })->isInstanceOf(\RuntimeException::class)
        ;
    }
}
