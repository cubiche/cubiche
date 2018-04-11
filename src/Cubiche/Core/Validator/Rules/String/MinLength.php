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
 * MinLength class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MinLength extends Rule
{
    /**
     * @var int
     */
    protected $minValue;

    /**
     * MinLength constructor.
     *
     * @param int $min
     */
    public function __construct($min)
    {
        $this->minValue = $min;
    }

    /**
     * @return int
     */
    public function minValue()
    {
        return $this->minValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->minValue
        );
    }
}
