<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\QueryBuilder;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Infrastructure\MongoDB\DocumentManager;
use MongoDB\Collection;

/**
 * QueryBuilder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class QueryBuilder
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Expression
     */
    protected $expression;

    /**
     * @var array
     */
    protected $options;

    /**
     * QueryBuilder constructor.
     *
     * @param DocumentManager $dm
     * @param string          $documentName
     */
    public function __construct(DocumentManager $dm, $documentName)
    {
        $this->dm = $dm;
        $this->classMetadata = $dm->getClassMetadata($documentName);
        $this->collection = $dm->getDocumentCollection($documentName);

        $this->expression = new Expression($this);
        $this->options = array();
    }

    /**
     * Returns a new Query set up with the builder configuration.
     *
     * @param Expression $expression
     *
     * @return Query
     */
    public function getQuery(Expression $expression = null)
    {
        if ($expression !== null) {
            return new Query(
                $this->classMetadata,
                $this->dm->getSerializer(),
                $this->collection,
                $expression->filters(),
                array(),
                $this->dm->queryLogger()
            );
        }

        return new Query(
            $this->classMetadata,
            $this->dm->getSerializer(),
            $this->collection,
            $this->expression->filters(),
            $this->options,
            $this->dm->queryLogger()
        );
    }

    /**
     * Return the expression's query criteria.
     *
     * @return array
     */
    public function getQueryArray()
    {
        return $this->expression->filters();
    }

    /**
     * @param string $option
     * @param mixed  $value
     *
     * @return $this
     */
    protected function queryOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Add one or more $and clauses to the current query.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function andX($expression /* , $expression2, ... */)
    {
        $this->expression->andX(...func_get_args());

        return $this;
    }

    /**
     * Add one or more $or clauses to the current query.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function orX($expression /* , $expression2, ... */)
    {
        $this->expression->orX(...func_get_args());

        return $this;
    }

    /**
     * Specify $all criteria for the current field.
     *
     * @param array $values
     *
     * @return $this
     */
    public function all(array $values)
    {
        $this->expression->all($values);

        return $this;
    }

    /**
     * Set the current field for building the expression.
     *
     * @param string $field
     *
     * @return $this
     */
    public function field($field)
    {
        $this->expression->field($field);

        return $this;
    }

    /**
     * Specify $elemMatch criteria for the current field.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function elemMatch($expression)
    {
        $this->expression->elemMatch($expression);

        return $this;
    }

    /**
     * Specify an equality match for the current field.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function equals($value)
    {
        $this->expression->equals($value);

        return $this;
    }

    /**
     * Specify $exists criteria for the current field.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function exists($bool)
    {
        $this->expression->exists((bool) $bool);

        return $this;
    }

    /**
     * Specify $gt criteria for the current field.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function gt($value)
    {
        $this->expression->gt($value);

        return $this;
    }

    /**
     * Specify $gte criteria for the current field.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function gte($value)
    {
        $this->expression->gte($value);

        return $this;
    }

    /**
     * Specify $lt criteria for the current field.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function lt($value)
    {
        $this->expression->lt($value);

        return $this;
    }

    /**
     * Specify $lte criteria for the current field.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function lte($value)
    {
        $this->expression->lte($value);

        return $this;
    }

    /**
     * Specify $in criteria for the current field.
     *
     * @param array $values
     *
     * @return $this
     */
    public function in(array $values)
    {
        $this->expression->in($values);

        return $this;
    }

    /**
     * Negates an expression for the current field.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function not($expression)
    {
        $this->expression->not($expression);

        return $this;
    }

    /**
     * Specify $ne criteria for the current field.
     *
     * @param string $value
     *
     * @return $this
     */
    public function notEqual($value)
    {
        $this->expression->notEqual($value);

        return $this;
    }

    /**
     * Specify $nin criteria for the current field.
     *
     * @param array $values
     *
     * @return $this
     */
    public function notIn(array $values)
    {
        $this->expression->notIn($values);

        return $this;
    }

    /**
     * Specify $size criteria for the current field.
     *
     * @param int $size
     *
     * @return $this
     */
    public function size($size)
    {
        $this->expression->size((int) $size);

        return $this;
    }

    /**
     * Sets the sort option.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function sort(array $fields)
    {
        return $this->queryOption('sort', $fields);
    }

    /**
     * Sets the limit option.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        return $this->queryOption('limit', $limit);
    }

    /**
     * Sets the skip(offset) option.
     *
     * @param int $skip
     *
     * @return $this
     */
    public function skip($skip)
    {
        return $this->queryOption('skip', $skip);
    }

    /**
     * Sets the projection option.
     *
     * @param array $projection
     *
     * @return $this
     */
    public function select($projection)
    {
        $this->queryOption('projection', array_fill_keys($projection, 1));

        if (!in_array('_id', $projection)) {
            $this->options['projection']['_id'] = -1;
        }

        return $this;
    }

    /**
     * Returns a new Expression.
     *
     * @return Expression
     */
    public function expr()
    {
        return new Expression($this);
    }
}
