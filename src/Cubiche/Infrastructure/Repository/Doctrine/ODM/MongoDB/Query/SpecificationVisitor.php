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
use Cubiche\Core\Selector\Count;
use Cubiche\Core\Selector\Custom;
use Cubiche\Core\Selector\Field;
use Cubiche\Core\Selector\Key;
use Cubiche\Core\Selector\Method;
use Cubiche\Core\Selector\Property;
use Cubiche\Core\Selector\SelectorInterface;
use Cubiche\Core\Selector\SelectorVisitorInterface;
use Cubiche\Core\Selector\This;
use Cubiche\Core\Selector\Value;
use Cubiche\Core\Specification\AndSpecification;
use Cubiche\Core\Specification\Constraint\BinaryConstraintOperator;
use Cubiche\Core\Specification\Constraint\Equal;
use Cubiche\Core\Specification\Constraint\GreaterThan;
use Cubiche\Core\Specification\Constraint\GreaterThanEqual;
use Cubiche\Core\Specification\Constraint\LessThan;
use Cubiche\Core\Specification\Constraint\LessThanEqual;
use Cubiche\Core\Specification\Constraint\NotEqual;
use Cubiche\Core\Specification\Constraint\NotSame;
use Cubiche\Core\Specification\Constraint\Same;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\NotSpecification;
use Cubiche\Core\Specification\OrSpecification;
use Cubiche\Core\Specification\Quantifier\All;
use Cubiche\Core\Specification\Quantifier\AtLeast;
use Cubiche\Core\Specification\Selector;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Specification\SpecificationVisitor as BaseSpecificationVisitor;
use Cubiche\Core\Visitor\VisiteeInterface;
use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\Model\IdInterface;

/**
 * Specification Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitor extends BaseSpecificationVisitor implements SelectorVisitorInterface
{
    use VisitorTrait;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitor::visit()
     */
    public function visit(VisiteeInterface $visitee)
    {
        if ($visitee instanceof SelectorInterface) {
            return $visitee->acceptSelectorVisitor($this);
        }

        return parent::visit($visitee);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitAnd()
     */
    public function visitAnd(AndSpecification $specification)
    {
        $leftQueryBuilder = $this->queryBuilderFromSpecification($specification->left());
        $rightQueryBuilder = $this->queryBuilderFromSpecification($specification->right());

        if ($this->hasSameOperator($leftQueryBuilder, $rightQueryBuilder)) {
            $this->queryBuilder->addAnd($leftQueryBuilder->getExpr());
            $this->queryBuilder->addAnd($rightQueryBuilder->getExpr());
        } else {
            $this->queryBuilder->addSearchCriteria($specification->left());
            $this->queryBuilder->addSearchCriteria($specification->right());
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitOr()
     */
    public function visitOr(OrSpecification $specification)
    {
        $leftQueryBuilder = $this->queryBuilderFromSpecification($specification->left());
        $rightQueryBuilder = $this->queryBuilderFromSpecification($specification->right());

        $this->queryBuilder->addOr($leftQueryBuilder->getExpr());
        $this->queryBuilder->addOr($rightQueryBuilder->getExpr());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitNot()
     */
    public function visitNot(NotSpecification $specification)
    {
        $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification());
        $this->queryBuilder->not($specificationQueryBuilder->getExpr());
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitSelector()
     */
    public function visitSelector(Selector $selector)
    {
        return $selector->selector()->accept($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        throw $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        throw $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitThis()
     */
    public function visitThis(This $specification)
    {
        throw $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitCustom()
     */
    public function visitCustom(Custom $specification)
    {
        throw $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitComposite()
     */
    public function visitComposite(Composite $specification)
    {
        $this->visitField($this->createFieldFromComposite($specification));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Selector\SelectorVisitorInterface::visitCount()
     */
    public function visitCount(Count $specification)
    {
        throw $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitGreaterThan()
     */
    public function visitGreaterThan(GreaterThan $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'gt'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'gte'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitLessThan()
     */
    public function visitLessThan(LessThan $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'lt'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitLessThanEqual()
     */
    public function visitLessThanEqual(LessThanEqual $specification)
    {
        $this->visitRelationalOperator($specification, Delegate::fromMethod($this->queryBuilder, 'lte'));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitEqual()
     */
    public function visitEqual(Equal $specification)
    {
        $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitNotEqual()
     */
    public function visitNotEqual(NotEqual $specification)
    {
        $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitSame()
     */
    public function visitSame(Same $specification)
    {
        $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitNotSame()
     */
    public function visitNotSame(NotSame $specification)
    {
        $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitAll()
     */
    public function visitAll(All $specification)
    {
        $field = $this->createField($specification->selector());
        $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification());
        $this->queryBuilder
            ->field($field->name())->all(
                $this->queryBuilder->expr()->elemMatch($specificationQueryBuilder->getExpr())->getQuery()
            );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationVisitorInterface::visitAtLeast()
     */
    public function visitAtLeast(AtLeast $specification)
    {
        if ($specification->count() === 1) {
            $field = $this->createField($specification->selector());
            $specificationQueryBuilder = $this->queryBuilderFromSpecification($specification->specification());
            $this->queryBuilder->field($field->name())->elemMatch($specificationQueryBuilder->getExpr());
        } else {
            throw $this->notSupportedException($specification);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder1
     * @param QueryBuilder $queryBuilder2
     *
     * @return bool
     */
    protected function hasSameOperator(QueryBuilder $queryBuilder1, QueryBuilder $queryBuilder2)
    {
        $intersection = new ArrayCollection(
            \array_intersect_key($queryBuilder1->getQueryArray(), $queryBuilder2->getQueryArray())
        );

        return $intersection->keys()->findOne(Criteria::custom(function ($value) {
            return \strpos($value, '$') === 0;
        })) !== null;
    }

    /**
     * @param BinaryConstraintOperator $operator
     * @param Delegate                 $addOperator
     */
    protected function visitRelationalOperator(BinaryConstraintOperator $operator, Delegate $addOperator)
    {
        $this->addFieldValueOperator($operator->left(), $operator->right(), $addOperator);
    }

    /**
     * @param BinaryConstraintOperator $operator
     */
    protected function visitEqualityOperator(BinaryConstraintOperator $operator)
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
     * @param BinaryConstraintOperator $operator
     */
    protected function visitNotEqualityOperator(BinaryConstraintOperator $operator)
    {
        $this->visitRelationalOperator($operator, Delegate::fromMethod($this->queryBuilder, 'notEqual'));
    }

    /**
     * @param SelectorInterface $selector
     * @param SelectorInterface $value
     * @param Delegate          $addOperator
     */
    private function addFieldValueOperator(
        SelectorInterface $selector,
        SelectorInterface $value,
        Delegate $addOperator
    ) {
        $actualValue = $this->createValue($value);
        $isEntityValue = false;
        if ($actualValue instanceof IdInterface) {
            $actualValue = $actualValue->toNative();
            $isEntityValue = true;
        }

        $field = $this->createField($selector, $isEntityValue);
        if ($field !== null) {
            $this->queryBuilder->field($field->name());
        }
        $addOperator($actualValue);
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
