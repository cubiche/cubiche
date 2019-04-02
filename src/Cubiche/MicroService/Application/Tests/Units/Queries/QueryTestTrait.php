<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Units\Queries;

use Cubiche\Core\Cqrs\Query\QueryInterface;
use Cubiche\Core\Validator\Validator;

/**
 * QueryTestTrait class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait QueryTestTrait
{
    /**
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * @return array
     */
    protected function validatorProvider()
    {
        return array();
    }

    /**
     * @param array $arguments
     *
     * @return QueryInterface
     */
    protected function createQuery(array $arguments)
    {
        $reflection = new \ReflectionClass($this->testedClass->getClass());

        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * Test class.
     */
    public function testClassInterface()
    {
        $this
            ->testedClass
                ->implements(QueryInterface::class)
        ;
    }

    /**
     * Test create.
     */
    public function testCreateQuery()
    {
        $query = $this->createQuery($this->getArguments());
        $reflection = new \ReflectionClass(get_class($query));

        //not really super useful, but at least it avoid having a query without parameters to be listed in the void list
        $this
            ->given()
            ->then()
                ->object($query)
                    ->isInstanceOf($this->testedClass->getClass());

        $parameters = $reflection->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            $methodName = $parameter->getName();
            if (in_array($methodName, ['messageName'])) {
                continue;
            }

            $proterty = $reflection->getProperty($parameter->getName());
            $proterty->setAccessible(true);
            $protertyValue = $proterty->getValue($query);

            // check that there is a method for every constructor argument
            // and returns the same value passed in the constructor
            $this
                ->given($value = $query->{$methodName}())
                ->then()
                    ->variable($value)
                        ->isEqualTo($protertyValue)
            ;
        }
    }

    /**
     * Test validator.
     */
    public function testValidateQuery()
    {
        foreach ($this->validatorProvider() as $provider) {
            $this
                ->given($query = $this->createQuery($provider['arguments']))
                ->then()
                    ->boolean(Validator::validate($query))
                        ->isEqualTo($provider['assert'])
            ;
        }
    }
}
