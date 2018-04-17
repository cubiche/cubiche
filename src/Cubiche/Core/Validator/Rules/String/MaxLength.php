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
 * MaxLength class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MaxLength extends Rule
{
    /**
     * @var int
     */
    protected $maxValue;

    /**
     * MaxLength constructor.
     *
     * @param int $max
     */
    public function __construct($max)
    {
        $this->maxValue = $max;
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
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->maxValue)
        );
    }
}
