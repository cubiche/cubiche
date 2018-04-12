<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules;

/**
 * ComparableRule class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ComparableRule extends Rule
{
    /**
     * @var mixed
     */
    protected $other;

    /**
     * ComparableRule constructor.
     *
     * @param $other
     */
    public function __construct($other)
    {
        $this->other = $other;

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function other()
    {
        return $this->other;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            !is_object($this->other) ?: spl_object_hash($this->other)
        );
    }
}
