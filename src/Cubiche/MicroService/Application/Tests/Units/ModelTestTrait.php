<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Tests\Units;

use Cubiche\Domain\EventSourcing\ReadModelInterface;
use Cubiche\Domain\Model\IdInterface;

/**
 * ModelTestTrait class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
trait ModelTestTrait
{
    /**
     * @var array
     */
    protected $methodNameMapping = array(
        'enabled' => 'isEnabled',
        'locked' => 'isLocked',
    );

    /**
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * @param array $arguments
     *
     * @return ReadModelInterface
     */
    protected function createReadModel(array $arguments)
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
                ->implements(ReadModelInterface::class)
        ;
    }

    /**
     * Test create.
     */
    public function testCreateReadModel()
    {
        $readModel = $this->createReadModel($this->getArguments());
        $reflection = new \ReflectionClass(get_class($readModel));

        $this
            ->then()
                // check that the readModels keep the message id
                ->variable($readModel->id())
                    ->isNotNull('Every read model should have a identifier.')
                ->object($readModel->id())
                    ->isInstanceOf(IdInterface::class)
        ;

        $parameters = $reflection->getConstructor()->getParameters();
        foreach ($parameters as $parameter) {
            $methodName = $parameter->getName();
            if (!method_exists($readModel, $methodName) && isset($this->methodNameMapping[$methodName])) {
                $methodName = $this->methodNameMapping[$methodName];
            }

            // check that there is a method for every constructor argument
            $this
                ->given($value = $readModel->{$methodName}())
                ->then()
                    ->variable($value)
                        ->isNotNull()
            ;
        }
    }
}
