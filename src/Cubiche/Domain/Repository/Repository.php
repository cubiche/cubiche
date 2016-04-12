<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Repository;

use Cubiche\Domain\Model\AggregateRootInterface;

/**
 * Repository Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var \ReflectionClass
     */
    protected $entityReflectionClass;

    /**
     * @param string $entityName
     */
    public function __construct($entityName)
    {
        $this->entityReflectionClass = new \ReflectionClass($entityName);
        if (!$this->entityReflectionClass->isSubclassOf(AggregateRootInterface::class)) {
            throw new \LogicException(\sprintf(
                '%s not implement %s, only the aggregate roots can have a repository class',
                $this->entityReflectionClass->name,
                AggregateRootInterface::class
            ));
        }
    }

    /**
     * @param mixed $item
     *
     * @throws \InvalidArgumentException
     */
    protected function checkType($item)
    {
        if (!is_object($item) || !$this->entityReflectionClass->isInstance($item)) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected %s instance, instance of %s given',
                $this->entityReflectionClass->name,
                \is_object($item) ? \gettype($item) : \get_class($item)
            ));
        }
    }
}
