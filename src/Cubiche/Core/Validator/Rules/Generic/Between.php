<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Generic;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Between class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Between extends Rule
{
    /**
     * @var int
     */
    protected $minValue;

    /**
     * @var int
     */
    protected $maxValue;

    /**
     * Between constructor.
     *
     * @param int $min
     * @param int $max
     */
    public function __construct($min, $max)
    {
        $this->minValue = $min;
        $this->maxValue = $max;
    }

    /**
     * @return int
     */
    public function minValue()
    {
        return $this->minValue;
    }

    /**
     * @return int
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
            json_encode($this->minValue),
            json_encode($this->maxValue)
        );
    }
}
