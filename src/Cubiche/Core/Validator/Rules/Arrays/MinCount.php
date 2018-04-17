<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Arrays;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * MinCount class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MinCount extends Rule
{
    /**
     * @var int
     */
    protected $minValue;

    /**
     * MinCount constructor.
     *
     * @param int $minValue
     */
    public function __construct($minValue)
    {
        $this->minValue = $minValue;

        parent::__construct();
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
            json_encode($this->minValue)
        );
    }
}
