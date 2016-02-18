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

use Cubiche\Domain\Collections\Specification\AndSpecification;
use Cubiche\Domain\Collections\Specification\Constraint\Equal;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\LessThan;
use Cubiche\Domain\Collections\Specification\Constraint\LessThanEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotEqual;
use Cubiche\Domain\Collections\Specification\Constraint\NotSame;
use Cubiche\Domain\Collections\Specification\Constraint\Same;
use Cubiche\Domain\Collections\Specification\NotSpecification;
use Cubiche\Domain\Collections\Specification\OrSpecification;
use Cubiche\Domain\Collections\Specification\Quantifier\All;
use Cubiche\Domain\Collections\Specification\Quantifier\AtLeast;
use Cubiche\Domain\Collections\Specification\Selector\Composite;
use Cubiche\Domain\Collections\Specification\Selector\Count;
use Cubiche\Domain\Collections\Specification\Selector\Custom;
use Cubiche\Domain\Collections\Specification\Selector\Field;
use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\Selector;
use Cubiche\Domain\Collections\Specification\Selector\This;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Specification\Specification;
use Cubiche\Domain\Collections\Specification\SpecificationInterface;
use Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Cubiche\Domain\Collections\Specification\Constraint\BinarySelectorOperator;

/**
 * Specification Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SpecificationVisitor implements SpecificationVisitorInterface
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string
     */
    protected $documentName;

    /**
     * @param DocumentManager $dm
     * @param string          $documentName
     */
    public function __construct(DocumentManager $dm, $documentName = null)
    {
        $this->dm = $dm;
        $this->documentName = $documentName;
    }

    /**
     * @param Specification $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    public function queryBuilder(Specification $specification)
    {
        return $this->visit($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visit()
     */
    public function visit(Specification $specification)
    {
        return $specification->visit($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAnd()
     */
    public function visitAnd(AndSpecification $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $leftQueryBuilder = $this->queryBuilder($specification->left());
        $rightQueryBuilder = $this->queryBuilder($specification->right());

        $builder->addAnd($leftQueryBuilder->currentExpr());
        $builder->addAnd($rightQueryBuilder->currentExpr());

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitOr()
     */
    public function visitOr(OrSpecification $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $leftQueryBuilder = $this->queryBuilder($specification->left());
        $rightQueryBuilder = $this->queryBuilder($specification->right());

        $builder->addOr($leftQueryBuilder->currentExpr());
        $builder->addOr($rightQueryBuilder->currentExpr());

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNot()
     */
    public function visitNot(NotSpecification $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $specificationQueryBuilder = $this->queryBuilder($specification->specification());

        $builder->not($specificationQueryBuilder->currentExpr());

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitValue()
     */
    public function visitValue(Value $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitKey()
     */
    public function visitKey(Key $specification)
    {
        return $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitProperty()
     */
    public function visitProperty(Property $specification)
    {
        return $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitMethod()
     */
    public function visitMethod(Method $specification)
    {
        return $this->visitField($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitThis()
     */
    public function visitThis(This $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitCustom()
     */
    public function visitCustom(Custom $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitComposite()
     */
    public function visitComposite(Composite $specification)
    {
        return $this->visitField($this->createFieldFromComposite($specification));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitCount()
     */
    public function visitCount(Count $specification)
    {
        $this->notSupportedException($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitGreaterThan()
     */
    public function visitGreaterThan(GreaterThan $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->gt($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitGreaterThanEqual()
     */
    public function visitGreaterThanEqual(GreaterThanEqual $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->gte($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitLessThan()
     */
    public function visitLessThan(LessThan $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->lt($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitLessThanEqual()
     */
    public function visitLessThanEqual(LessThanEqual $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->lte($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitEqual()
     */
    public function visitEqual(Equal $specification)
    {
        return $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNotEqual()
     */
    public function visitNotEqual(NotEqual $specification)
    {
        return $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitSame()
     */
    public function visitSame(Same $specification)
    {
        return $this->visitEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitNotSame()
     */
    public function visitNotSame(NotSame $specification)
    {
        return $this->visitNotEqualityOperator($specification);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAll()
     */
    public function visitAll(All $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->selector());
        $specificationQueryBuilder = $this->queryBuilder($specification->specification()->not());

        return $builder->not(
            $builder->expr()->field($field->name())->elemMatch($specificationQueryBuilder->currentExpr())
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Domain\Collections\Specification\SpecificationVisitorInterface::visitAtLeast()
     */
    public function visitAtLeast(AtLeast $specification)
    {
        if ($specification->count() === 1) {
            $builder = new QueryBuilder($this->dm, $this->documentName);
            $field = $this->createField($specification->selector());
            $specificationQueryBuilder = $this->queryBuilder($specification->specification());

            return $builder->field($field->name())->elemMatch($specificationQueryBuilder->currentExpr());
        }

        $this->notSupportedException($specification);
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
     * @param BinarySelectorOperator $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitEqualityOperator(BinarySelectorOperator $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->equals($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * @param BinarySelectorOperator $specification
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitNotEqualityOperator(BinarySelectorOperator $specification)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $field = $this->createField($specification->left());
        $builder->field($field->name())->notEqual($this->createValue($specification->right()));

        return $builder;
    }

    /**
     * @param Field $field
     *
     * @return \Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB\QueryBuilder
     */
    protected function visitField(Field $field)
    {
        $builder = new QueryBuilder($this->dm, $this->documentName);
        $builder->field($field->name())->equals(true);

        return $builder;
    }

    /**
     * @param Composite $specification
     *
     * @return \Cubiche\Domain\Collections\Specification\Selector\Field
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
     * @return \Cubiche\Domain\Collections\Specification\Selector\Field
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
}
