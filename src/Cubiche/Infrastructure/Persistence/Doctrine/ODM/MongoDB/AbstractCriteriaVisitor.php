<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Model\EntityInterface;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Field;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;

/**
 * Abstract Criteria Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class AbstractCriteriaVisitor
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param Composite $specification
     *
     * @return \Cubiche\Domain\Specification\Selector\Field
     */
    protected function createFieldFromComposite(Composite $specification)
    {
        $valueField = $this->createField($specification->valueSelector());
        $applyField = $this->createField($specification->applySelector());

        return new Property(\sprintf('%s.%s', $valueField->name(), $applyField->name()));
    }

    /**
     * @param SelectorInterface $selector
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Domain\Specification\Selector\Field
     */
    protected function createField(SelectorInterface $selector)
    {
        if ($selector instanceof Field) {
            return $selector;
        }

        if ($selector instanceof This) {
            return new Property('id');
        }

        if ($selector instanceof Composite) {
            return $this->createFieldFromComposite($selector);
        }

        throw new \LogicException(\sprintf(
            'The %s specification cannot be used in the field name',
            \get_class($selector)
        ));
    }

    /**
     * @param SpecificationInterface $value
     *
     * @throws \LogicException
     *
     * @return mixed
     */
    protected function createValue(SpecificationInterface $value)
    {
        if ($value instanceof Value) {
            $actualValue = $value->value();
            if ($actualValue instanceof EntityInterface) {
                return $actualValue->id()->toNative();
            }

            return $actualValue;
        }

        throw new \LogicException(\sprintf(
            'The %s specification cannot be used as a value specification',
            \get_class($value)
        ));
    }

    /**
     * @param mixed $operation
     *
     * @throws \LogicException
     */
    protected function notSupportedException($operation)
    {
        throw new \LogicException(
            \sprintf('The %s operation is not supported by Doctrine\ODM\MongoDB', \get_class($operation))
        );
    }
}
