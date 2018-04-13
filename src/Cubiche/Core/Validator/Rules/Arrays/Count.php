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
 * Count class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Count extends Rule
{
    /**
     * @var int
     */
    protected $value;

    /**
     * Count constructor.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        $this->value = $value;

        parent::__construct();
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->value)
        );
    }
}
