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

/**
 * Expression class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Expression
{
    /**
     * @var array
     */
    protected $filters;

    /**
     * @var string
     */
    protected $currentField;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Expression constructor.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->filters = array();
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Add one or more $and clauses to the current query.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function andX($expression /*, $expression2, ... */)
    {
        $this->prepareFilterIndex('$and');

        $this->filters['$and'] = array_merge(
            $this->filters['$and'],
            $this->mapExpressions(func_get_args())
        );

        return $this;
    }

    /**
     * Add one or more $or clauses to the current query.
     *
     * @param array|Expression $expression
     *
     * @return $this
     */
    public function orX($expression /*, $expression2, ... */)
    {
        $this->prepareFilterIndex('$or');

        $this->filters['$or'] = array_merge(
            $this->filters['$or'],
            $this->mapExpressions(func_get_args())
        );

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
        return $this->operator('$all', (array) $values);
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
        if ($field == 'id') {
            $field = '_id';
        }

        $this->currentField = (string) $field;

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
        return $this->operator('$elemMatch', $expression instanceof self ? $expression->filters() : $expression);
    }

    /**
     * Specify an equality match for the current field.
     *
     * @param string $value
     *
     * @return $this
     */
    public function equals($value)
    {
        if ($this->currentField) {
            $this->filters[$this->currentField] = $value;
        } else {
            $this->filters = $value;
        }

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
        return $this->operator('$exists', (bool) $bool);
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
        return $this->operator('$gt', $value);
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
        return $this->operator('$gte', $value);
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
        return $this->operator('$lt', $value);
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
        return $this->operator('$lte', $value);
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
        return $this->operator('$in', array_values($values));
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
        return $this->operator('$not', $expression instanceof self ? $expression->filters() : $expression);
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
        return $this->operator('$ne', $value);
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
        return $this->operator('$nin', array_values($values));
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
        return $this->operator('$size', (int) $size);
    }

    /**
     * @param string $operator
     */
    private function prepareFilterIndex($operator)
    {
        if (!isset($this->filters[$operator])) {
            $this->filters[$operator] = array();
        }
    }

    /**
     * @param  $expressions
     *
     * @return array
     */
    private function mapExpressions($expressions)
    {
        return array_map(
            function ($expression) {
                return $expression instanceof Expression ? $expression->filters() : $expression;
            },
            func_get_args()
        );
    }

    /**
     * Defines an operator and value on the expression.
     *
     * If there is a current field, the operator will be set on it; otherwise,
     * the operator is set at the top level of the filters.
     *
     * @param string $operator
     * @param mixed  $value
     *
     * @return $this
     */
    protected function operator($operator, $value)
    {
        $this->wrapEqualityCriteria();

        if ($this->currentField) {
            $this->filters[$this->currentField][$operator] = $value;
        } else {
            $this->filters[$operator] = $value;
        }

        return $this;
    }

    /**
     * Wraps equality criteria with an operator.
     *
     * If equality criteria was previously specified for a field, it cannot be
     * merged with other operators without first being wrapped in an operator of
     * its own. Ideally, we would wrap it with $eq, but that is only available
     * in MongoDB 2.8. Using a single-element $in is backwards compatible.
     *
     * @see Expr::operator()
     */
    private function wrapEqualityCriteria()
    {
        /* If the current field has no criteria yet, do nothing. This ensures
         * that we do not inadvertently inject {"$in": null} into the filters.
         */
        if ($this->currentField && !isset($this->filters[$this->currentField]) &&
            !array_key_exists($this->currentField, $this->filters)) {
            return;
        }

        if ($this->currentField) {
            $filters = &$this->filters[$this->currentField];
        } else {
            $filters = &$this->filters;
        }

        /* If the filters is an empty array, we'll assume that the user has not
         * specified criteria. Otherwise, check if the array includes a filters
         * operator (checking the first key is sufficient). If neither of these
         * conditions are met, we'll wrap the filters value with $in.
         */
        if (is_array($filters) && (empty($filters) || strpos(key($filters), '$') === 0)) {
            return;
        }

        $filters = ['$in' => [$filters]];
    }

    /**
     * Returns the filters generated by the expression.
     *
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Returns a new Query set up with the builder configuration.
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->queryBuilder->getQuery($this);
    }
}
