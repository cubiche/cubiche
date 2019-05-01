<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Units\Commands;

use Cubiche\Core\Bus\Command\CommandInterface;
use Cubiche\Core\Validator\Validator;

/**
 * CommandTestTrait class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait CommandTestTrait
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
     * @return CommandInterface
     */
    protected function createCommand(array $arguments)
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
                ->implements(CommandInterface::class)
        ;
    }

    /**
     * Test create.
     */
    public function testCreateCommand()
    {
        $command = $this->createCommand($this->getArguments());
        $reflection = new \ReflectionClass(get_class($command));

        $parameters = $reflection->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            $methodName = $parameter->getName();

            $proterty = $reflection->getProperty($parameter->getName());
            $proterty->setAccessible(true);
            $protertyValue = $proterty->getValue($command);

            // check that there is a method for every constructor argument
            // and returns the same value passed in the constructor
            $this
                ->given($value = $command->{$methodName}())
                ->then()
                    ->variable($value)
                        ->isEqualTo($protertyValue)
            ;
        }
    }

    /**
     * @dataProvider validatorProvider
     */
    public function testValidateCommand($assert, $arguments)
    {
        $this
            ->given($command = $this->createCommand($arguments))
            ->then()
                ->boolean(Validator::validate($command))
                    ->isEqualTo($assert)
        ;
    }
}
