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
     * @var int
     */
    protected $length;

    /**
     * Length constructor.
     *
     * @param int $length
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->length)
        );
    }
}
