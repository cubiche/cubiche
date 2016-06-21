<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

/**
 * Selector Factory Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SelectorFactory implements SelectorFactoryInterface
{
    /**
     * @var \ReflectionClass[]
     */
    private $selectorClass;

    /**
     * @var string[]
     */
    private $namespaces;

    /**
     * @param string $defaultNamespace
     */
    public function __construct($defaultNamespace = null)
    {
        $this->selectorClass = array();
        $this->namespaces = array();

        if (!empty($defaultNamespace)) {
            $this->addNamespace($defaultNamespace);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addSelector($selectorClass, $selectorName = null)
    {
        $reflection = $this->validateSelectorClass($selectorClass);

        if (empty($selectorName)) {
            $selectorName = \lcfirst($reflection->getShortName());
        }

        if ($this->findSelector($selectorName) !== null) {
            throw new \InvalidArgumentException(\sprintf('There is already a selector with name %s', $selectorName));
        }

        $this->selectorClass[$selectorName] = $reflection;
    }

    /**
     * {@inheritdoc}
     */
    public function addNamespace($namespace)
    {
        if (empty($namespace)) {
            throw new \InvalidArgumentException('The namespace name must be non-empty');
        }

        if (!isset($this->namespaces[$namespace])) {
            $this->namespaces[] = $namespace;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create($selectorName, array $arguments = array())
    {
        $reflection = $this->findSelector($selectorName);

        if ($reflection !== null) {
            return $reflection->newInstanceArgs($arguments);
        }

        throw new \InvalidArgumentException(\sprintf('There is not a selector with name "%s"', $selectorName));
    }

    /**
     * @param string $selectorName
     *
     * @return \ReflectionClass
     */
    private function findSelector($selectorName)
    {
        if (isset($this->selectorClass[$selectorName])) {
            return $this->selectorClass[$selectorName];
        }

        foreach ($this->namespaces as $namespace) {
            $className = $namespace.'\\'.\ucfirst($selectorName);
            $reflection = $this->validateSelectorClass($className, false);
            if ($reflection !== null) {
                $this->selectorClass[$selectorName] = $reflection;

                return $reflection;
            }
        }

        return;
    }

    /**
     * @param string $selectorClass
     * @param bool   $strict
     */
    private function validateSelectorClass($selectorClass, $strict = true)
    {
        if (!\class_exists($selectorClass)) {
            return $this->nullOrException(
                new \InvalidArgumentException(\sprintf('There is not a class with name %s', $selectorClass)),
                $strict
            );
        }

        $reflection = new \ReflectionClass($selectorClass);
        if (!$reflection->isSubclassOf(SelectorInterface::class)) {
            return $this->nullOrException(
                new \InvalidArgumentException(
                    \sprintf('%s must be implement %s interface', $selectorClass, SelectorInterface::class)
                ),
                $strict
            );
        }

        return $reflection;
    }

    /**
     * @param \Exception $exception
     * @param bool       $strict
     *
     * @throws \Exception
     */
    private function nullOrException(\Exception $exception, $strict = true)
    {
        if ($strict) {
            throw $exception;
        }

        return;
    }
}
