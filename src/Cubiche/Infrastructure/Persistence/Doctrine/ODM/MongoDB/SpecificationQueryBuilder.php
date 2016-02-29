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

use Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface;
use Cubiche\Domain\Collections\Comparator\SelectorComparator;
use Cubiche\Domain\Comparable\Comparator;
use Cubiche\Domain\Comparable\ComparatorInterface;
use Cubiche\Domain\Comparable\Custom as CustomComparator;
use Cubiche\Domain\Comparable\MultiComparator;
use Cubiche\Domain\Delegate\Delegate;
use Cubiche\Domain\Specification\AndSpecification;
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
use Cubiche\Domain\Specification\SpecificationInterface;
use Cubiche\Domain\Specification\SpecificationVisitorInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;

/**
 * Specification Query Builder Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationQueryBuilder extends Builder implements SpecificationVisitorInterface, ComparatorVisitorInterface
{
    /**
     * @var Delegate
     */
    protected $factory;

    /**
     * @param DocumentManager        $dm
     * @param string                 $documentName
     * @param SpecificationInterface $specification
     */
    public function __construct(DocumentManager $dm, $documentName = null, SpecificationInterface $specification = null)
    {
        parent::__construct($dm, $documentName);

        $this->factory = Delegate::fromClosure(
            function (SpecificationInterface $specification) use ($dm, $documentName) {
                return new SpecificationQueryBuilder($dm, $documentName, $specification);
            }
        );

        if ($specification !== null) {
            $specification->accept($this);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAnd()
     */
    public function visitAnd(AndSpecification $specification)
    {
        $leftQueryBuilder = $this->queryBuilder($specification->left());
        $rightQueryBuilder = $this->queryBuilder($specification->right());

        $this->addAnd($leftQueryBuilder->currentExpr());
        $this->addAnd($rightQueryBuilder->currentExpr());

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitOr()
     */
    public function visitOr(OrSpecification $specification)
    {
        $leftQueryBuilder = $this->queryBuilder($specification->left());
        $rightQueryBuilder = $this->queryBuilder($specification->right());

        $this->addOr($leftQueryBuilder->currentExpr());
        $this->addOr($rightQueryBuilder->currentExpr());

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNot()
     */
    public function visitNot(NotSpecification $specification)
    {
        $specificationQueryBuilder = $this->queryBuilder($specification->specification());
        $this->not($specificationQueryBuilder->currentExpr());

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        return $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        return  $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        return $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        return $this->visitField($specification);
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
        return $this->visitField($this->createFieldFromComposite($specification));
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
        $field = $this->createField($specification->left());
        $this->field($field->name())->gt($this->createValue($specification->right()));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification)
    {
        $field = $this->createField($specification->left());
        $this->field($field->name())->gte($this->createValue($specification->right()));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThan()
     */
    public function visitLessThan(LessThan $specification)
    {
        $field = $this->createField($specification->left());
        $this->field($field->name())->lt($this->createValue($specification->right()));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitLessThanEqual()
     */
    public function visitLessThanEqual(LessThanEqual $specification)
    {
        $field = $this->createField($specification->left());
        $this->field($field->name())->lte($this->createValue($specification->right()));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitEqual()
     */
    public function visitEqual(Equal $specification)
    {
        return $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotEqual()
     */
    public function visitNotEqual(NotEqual $specification)
    {
        return $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitSame()
     */
    public function visitSame(Same $specification)
    {
        return $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitNotSame()
     */
    public function visitNotSame(NotSame $specification)
    {
        return $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Specification\SpecificationVisitorInterface::visitAll()
     */
    public function visitAll(All $specification)
    {
        $field = $this->createField($specification->selector());
        $specificationQueryBuilder = $this->queryBuilder($specification->specification()->not());

        return $this->not(
            $this->expr()->field($field->name())->elemMatch($specificationQueryBuilder->currentExpr())
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
            $specificationQueryBuilder = $this->queryBuilder($specification->specification());

            return $this->field($field->name())->elemMatch($specificationQueryBuilder->currentExpr());
        }

        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitComparator()
     */
    public function visitComparator(Comparator $comparator)
    {
        return $this->notSupportedComparator($comparator);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitCustomComparator()
     */
    public function visitCustomComparator(CustomComparator $comparator)
    {
        return $this->notSupportedComparator($comparator);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Comparable\ComparatorVisitorInterface::visitMultiComparator()
     */
    public function visitMultiComparator(MultiComparator $comparator)
    {
        $comparator->firstComparator()->accept($this);
        $comparator->secondComparator()->accept($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Comparator\ComparatorVisitorInterface::visitSelectorComparator()
     */
    public function visitSelectorComparator(SelectorComparator $comparator)
    {
        $field = $this->createField($comparator->selector());
        $this->sort($field->name(), $comparator->order()->toNative());

        return $this;
    }

    /**
     * @param BinarySelectorOperator $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitEqualityOperator(BinarySelectorOperator $specification)
    {
        $field = $this->createField($specification->left());
        $this->field($field->name())->equals($this->createValue($specification->right()));

        return $this;
    }

    /**
     * @param BinarySelectorOperator $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitNotEqualityOperator(BinarySelectorOperator $specification)
    {
        $field = $this->createField($specification->left());
        $this->field($field->name())->notEqual($this->createValue($specification->right()));

        return $this;
    }

    /**
     * @param Field $field
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitField(Field $field)
    {
        $this->field($field->name())->equals(true);

        return $this;
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
     * @param Selector $selector
     *
     * @throws \LogicException
     *
     * @return \Cubiche\Domain\Specification\Selector\Field
     */
    protected function createField(Selector $selector)
    {
        if ($selector instanceof Field) {
            return $selector;
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
            return $value->value();
        }

        throw new \LogicException(\sprintf(
            'The %s specification cannot be used as a value specification',
            \get_class($value)
        ));
    }

    /**
     * @return string
     */
    protected function currentField()
    {
        return $this->currentField;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\Query\Expr
     */
    protected function currentExpr()
    {
        $expr = $this->expr();
        $expr->setQuery($this->getQueryArray());

        return $expr;
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return SpecificationQueryBuilder
     */
    protected function queryBuilder(SpecificationInterface $specification)
    {
        return $this->factory->__invoke($specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @throws \LogicException
     */
    protected function notSupportedException(SpecificationInterface $specification)
    {
        throw new \LogicException(
            \sprintf('The %s specification is not supported by Doctrine\ODM\MongoDB', \get_class($specification))
        );
    }

    /**
     * @param ComparatorInterface $comparator
     *
     * @throws \LogicException
     */
    protected function notSupportedComparator(ComparatorInterface $comparator)
    {
        throw new \LogicException(
            \sprintf('The %s comparator is not supported by Doctrine\ODM\MongoDB', \get_class($comparator))
        );
    }
}
