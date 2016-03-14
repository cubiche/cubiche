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

use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\Specification\AndSpecification;
use Cubiche\Domain\Specification\BinarySpecification;
use Cubiche\Domain\Specification\Constraint\BinarySelectorOperator;
use Cubiche\Domain\Specification\Constraint\Equal;
use Cubiche\Domain\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Specification\Constraint\LessThan;
use Cubiche\Domain\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Specification\Constraint\NotEqual;
use Cubiche\Domain\Specification\Constraint\NotSame;
use Cubiche\Domain\Specification\Constraint\Same;
use Cubiche\Domain\Specification\NotSpecification;
use Cubiche\Domain\Specification\OrSpecification;
use Cubiche\Domain\Specification\Quantifier\All;
use Cubiche\Domain\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Count;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Field;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\Selector;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;
use Cubiche\Domain\Specification\SelectorInterface;
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;

/**
 * Specification Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitor extends AbstractCriteriaVisitor implements SpecificationVisitorInterface
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAnd()
     */
    public function visitAnd(AndSpecification $specification)
    {
        $this->visitLogicalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'addAnd'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitOr()
     */
    public function visitOr(OrSpecification $specification)
    {
        $this->visitLogicalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'addOr'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNot()
     */
    public function visitNot(NotSpecification $specification)
    {
        $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification());
        $this->queryBuilder->not($specificationQueryBuilder->currentExpr());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitThis()
     */
    public function visitThis(This $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitCustom()
     */
    public function visitCustom(Custom $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitComposite()
     */
    public function visitComposite(Composite $specification)
    {
        $this->visitField($this->createFieldFromComposite($specification));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitCount()
     */
    public function visitCount(Count $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitGreaterThan()
     */
    public function visitGreaterThan(GreaterThan $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'gt'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'gte'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThan()
     */
    public function visitLessThan(LessThan $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'lt'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThanEqual()
     */
    public function visitLessThanEqual(LessThanEqual $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'lte'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitEqual()
     */
    public function visitEqual(Equal $specification)
    {
        $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotEqual()
     */
    public function visitNotEqual(NotEqual $specification)
    {
        $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitSame()
     */
    public function visitSame(Same $specification)
    {
        $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotSame()
     */
    public function visitNotSame(NotSame $specification)
    {
        $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAll()
     */
    public function visitAll(All $specification)
    {
        $field = $this->createField($specification->selector());
        $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification()->not());

        $this->queryBuilder->not(
            $this->queryBuilder->expr()->field($field->name())->elemMatch($specificationQueryBuilder->getExpr())
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAtLeast()
     */
    public function visitAtLeast(AtLeast $specification)
    {
        if ($specification->count() === 1) {
            $field = $this->createField($specification->selector());
            $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification());

            $this->queryBuilder->field($field->name())->elemMatch($specificationQueryBuilder->currentExpr());
        } else {
            $this->notSupportedException($specification);
        }
    }

    /**
     * @param BinarySpecification $operator
     * @param Delegate            $addOperator
     */
    protected function visitLogicalOperator(BinarySpecification $operator, Delegate $addOperator)
    {
        $leftQueryBuilder = $this->queryBuilderFromSpecification($operator->left());
        $rightQueryBuilder = $this->queryBuilderFromSpecification($operator->right());

        $addOperator($leftQueryBuilder->getExpr());
        $addOperator($rightQueryBuilder->getExpr());
    }

    /**
     * @param BinarySelectorOperator $operator
     * @param Delegate               $addOperator
     */
    protected function visitRelationalOperator(BinarySelectorOperator $operator, Delegate $addOperator)
    {
        $this->addFieldValueOperator($operator->left(), $operator->right(), $addOperator);
    }

    /**
     * @param BinarySelectorOperator $operator
     */
    protected function visitEqualityOperator(BinarySelectorOperator $operator)
    {
        $selector = $operator->left();
        if ($selector instanceof Composite && $selector->applySelector() instanceof Count) {
            $this->addFieldValueOperator(
                $selector->valueSelector(),
                $operator->right(),
                Delegate::fromMethod($this->queryBuilder, 'size')
            );
        } else {
            $this->visitRelationalOperator($operator, Delegate::fromMethod($this->queryBuilder, 'equals'));
        }
    }

    /**
     * @param BinarySelectorOperator $operator
     */
    protected function visitNotEqualityOperator(BinarySelectorOperator $operator)
    {
        $this->visitRelationalOperator($operator, Delegate::fromMethod($this->queryBuilder, 'notEqual'));
    }

    /**
     * @param SelectorInterface      $selector
     * @param SpecificationInterface $value
     * @param Delegate               $addOperator
     */
    private function addFieldValueOperator(
        SelectorInterface $selector,
        SpecificationInterface $value,
        Delegate $addOperator
    ) {
        $this->queryBuilder->field($this->createField($selector)->name());
        $addOperator($this->createValue($value));
    }

    /**
     * @param Field $field
     */
    protected function visitField(Field $field)
    {
        $this->queryBuilder->field($field->name())->equals(true);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return QueryBuilder
     */
    protected function queryBuilderFromSpecification(SpecificationInterface $specification)
    {
        return $this->queryBuilder->createQueryBuilder()->addSearchCriteria($specification);
    }
}
