<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Repository\Doctrine\ODM\MongoDB\Query;

use Cubiche\Core\Selector\Composite;
use Cubiche\Core\Selector\Field;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Specification\Selector;
use Cubiche\Domain\Model\EntityInterface;
use Cubiche\Domain\Model\NativeValueObjectInterface;

/**
 * Visitor Class Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait VisitorTrait
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @param Composite $specification
     *
     * @return \Cubiche\Core\Selector\Field
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
     * @return \Cubiche\Core\Selector\Field
     */
    protected function createField(SelectorInterface $selector, $isEntityValue = false)
    {
        if ($selector instanceof Selector) {
            return $this->createField($selector->selector(), $isEntityValue);
        }

        if ($selector instanceof Field) {
            return $selector;
        }

        if ($selector instanceof This) {
            return $isEntityValue ? new Property('id') : null;
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
     * @param SelectorInterface $value
     *
     * @throws \LogicException
     *
     * @return mixed
     */
    protected function createValue(SelectorInterface $value)
    {
        if ($value instanceof Value) {
            $actualValue = $value->value();
            if ($actualValue instanceof EntityInterface) {
                return $actualValue->id();
            }
            if ($actualValue instanceof NativeValueObjectInterface) {
                return $actualValue->toNative();
            }

            return $actualValue;
        }

        throw new \LogicException(\sprintf(
            'The %s selector cannot be used as a value selector',
            \get_class($value)
        ));
    }

    /**
     * @param mixed $operation
     *
     * @return \LogicException
     */
    protected function notSupportedException($operation)
    {
        return new \LogicException(
            \sprintf('The %s operation is not supported by Doctrine\ODM\MongoDB', \get_class($operation))
        );
    }
}
