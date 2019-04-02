<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Tests\Units;

use Closure;
use Cubiche\BoundedContext\Application\BoundedContextInterface;
use Cubiche\BoundedContext\Application\Configuration\ServiceHelperTrait;
use Cubiche\BoundedContext\Application\Tests\Fixtures\TokenContext;
use Cubiche\Core\Cqrs\Command\CommandBus;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Core\EventBus\Event\EventBus;
use Cubiche\Tests\TestCase as BaseTestCase;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * BoundedContextTestCase class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BoundedContextTestCase extends BaseTestCase
{
    use ServiceHelperTrait {
        getServiceAlias as traitGetServiceAlias;
    }

    /**
     * @var BoundedContextInterface
     */
    protected $context;

    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param Closure   $reflectionClassFactory
     * @param Closure   $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        Closure $reflectionClassFactory = null,
        Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Equatable\Tests\Asserters');
        $this->getAsserterGenerator()->addNamespace('Cubiche\Core\Collections\Tests\Asserters');

        $this->getAssertionManager()->setAlias('variable', 'VariableAsserter');
        $this->getAssertionManager()->setAlias('object', 'ObjectAsserter');
        $this->getAssertionManager()->setAlias('collection', 'CollectionAsserter');
        $this->getAssertionManager()->setAlias('list', 'ListAsserter');
        $this->getAssertionManager()->setAlias('set', 'SetAsserter');
        $this->getAssertionManager()->setAlias('hashmap', 'HashMapAsserter');
        $this->getAssertionManager()->setAlias('datasource', 'DataSourceAsserter');

        $this->context = $this->createBoundedContext();
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    protected function get($id)
    {
        $ref = new \ReflectionMethod($this->context, 'get');
        $ref->setAccessible(true);

        return $ref->invoke($this->context, $id);
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    protected function getServiceAlias($type, $name = null)
    {
        $namespace = null;
        if ($position = strrpos($name, '.')) {
            $namespace = substr($name, 0, $position);
            $name = substr($name, $position + 1);
        }

        if ($name !== null) {
            return sprintf('%s.%s.%s', $namespace ?? $this->getContextNamespace(), $type, $name);
        }

        return sprintf('%s.%s', $namespace ?? $this->getContextNamespace(), $type);
    }

    /**
     * @return string
     */
    protected function getContextNamespace()
    {
        return $this->context->getNamespace();
    }

    /**
     * @return CommandBus
     */
    protected function commandBus()
    {
        $ref = new \ReflectionMethod($this->context, 'commandBus');
        $ref->setAccessible(true);

        return $ref->invoke($this->context);
    }

    /**
     * @return QueryBus
     */
    protected function queryBus()
    {
        $ref = new \ReflectionMethod($this->context, 'queryBus');
        $ref->setAccessible(true);

        return $ref->invoke($this->context);
    }

    /**
     * @return EventBus
     */
    protected function eventBus()
    {
        $ref = new \ReflectionMethod($this->context, 'eventBus');
        $ref->setAccessible(true);

        return $ref->invoke($this->context);
    }

    /**
     * @return BoundedContextInterface
     */
    abstract protected function createBoundedContext();
}
