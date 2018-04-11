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
 * MaxCount class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MaxCount extends Rule
{
    /**
     * @var int
     */
    protected $maxValue;

    /**
     * MaxCount constructor.
     *
     * @param int $maxValue
     */
    public function __construct($maxValue)
    {
        $this->maxValue = $maxValue;

        parent::__construct();
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
            $this->maxValue
        );
    }
}
