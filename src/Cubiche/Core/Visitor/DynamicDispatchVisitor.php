<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor;

/**
 * Abstract Dynamic Dispatch Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class DynamicDispatchVisitor implements DynamicDispatchVisitorInterface
{
    /**
     * @var \ReflectionMethod[]
     */
    private $visitorMethods = null;

    /**
     * @var \ReflectionMethod[]
     */
    private $handlerMethods = array();

    /**
     * @var ResolverVisitorMethodInterface
     */
    protected $resolver;

    /**
     * @param ResolverVisitorMethodInterface $resolver
     */
    public function __construct(ResolverVisitorMethodInterface $resolver = null)
    {
        $this->resolver = $resolver === null ? new ResolverVisitorMethod() : $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandlerVisitee(VisiteeInterface $visitee)
    {
        return $this->handlerMethod($visitee) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(VisiteeInterface $visitee)
    {
        return $this->visitWith($visitee, \func_get_args());
    }

    /**
     * @param VisiteeInterface $visitee
     * @param array            $args
     *
     * @return mixed
     */
    protected function visitWith(VisiteeInterface $visitee, array $args)
    {
        $method = $this->handlerMethod($visitee);

        if ($method !== null) {
            return $method->invokeArgs($this, $args);
        }

        throw $this->notSupportedVisiteeException($visitee);
    }

    /**
     * @param VisiteeInterface $visitee
     *
     * @return \LogicException
     */
    protected function notSupportedVisiteeException(VisiteeInterface $visitee)
    {
        return new \LogicException(
            \sprintf('The %s visitee is not supported by %s visitor', \get_class($visitee), static::class)
        );
    }

    /**
     * @param VisiteeInterface $visitee
     *
     * @return \ReflectionMethod|null
     */
    private function handlerMethod(VisiteeInterface $visitee)
    {
        $class = new \ReflectionClass(\get_class($visitee));
        if (isset($this->handlerMethods[$class->name])) {
            return $this->handlerMethods[$class->name];
        }
        if ($this->visitorMethods === null) {
            $this->visitorMethods = $this->visitorMethods();
        }

        return $this->findHandlerMethod($class);
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return \ReflectionMethod|null
     */
    private function findHandlerMethod(\ReflectionClass $class)
    {
        $current = $class;
        while ($current !== false) {
            if (isset($this->visitorMethods[$current->name])) {
                $this->handlerMethods[$class->name] = $this->visitorMethods[$current->name];

                return $this->handlerMethods[$class->name];
            }

            $current = $current->getParentClass();
        }

        return;
    }

    /**
     * @return \ReflectionMethod[]
     */
    private function visitorMethods()
    {
        $visitorMethods = array();
        $reflection = new \ReflectionClass(static::class);
        /** @var \ReflectionMethod $method */
        foreach ($reflection->getMethods() as $method) {
            if ($method->getName() !== 'visit') {
                $visiteeClass = $this->resolver->resolveVisiteeClass($method);
                if ($visiteeClass !== null) {
                    $visitorMethods[$visiteeClass->getName()] = $method;
                }
            }
        }

        return $visitorMethods;
    }
}
