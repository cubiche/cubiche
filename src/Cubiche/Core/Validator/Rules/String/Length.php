<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\String;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Length class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Length extends Rule
{
    /**
     * @var int|null
     */
    protected $minValue;

    /**
     * @var int|null
     */
    protected $maxValue;

    /**
     * Length constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct($min = null, $max = null)
    {
        $this->minValue = $min;
        $this->maxValue = $max;

//        Assert::oneOf()
//        $paramValidator = new OneOf(new Numeric(), new NullType());
//        if (!$paramValidator->validate($min)) {
//            throw new ComponentException(
//                sprintf('%s is not a valid numeric length', $min)
//            );
//        }

//        if (!$paramValidator->validate($max)) {
//            throw new ComponentException(
//                sprintf('%s is not a valid numeric length', $max)
//            );
//        }

//        if (!is_null($min) && !is_null($max) && $min > $max) {
//            throw new ComponentException(
//                sprintf('%s cannot be less than %s for validation', $min, $max)
//            );
//        }
    }

    /**
     * @return int|null
     */
    public function minValue()
    {
        return $this->minValue;
    }

    /**
     * @return int|null
     */
    public function maxValue()
    {
        return $this->maxValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s-%s',
            $this->shortClassName(),
            $this->minValue,
            $this->maxValue
        );
    }
}
