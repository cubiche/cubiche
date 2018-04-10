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

use Cubiche\Core\Visitor\Visitee;

/**
 * Rule class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Rule extends Visitee
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Rule constructor.
     */
    public function __construct()
    {
        $this->setId();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;

        return md5($this->id);
    }

    /**
     * Set the rule identifier.
     */
    protected function setId()
    {
        $this->id = $this->shortClassName();
    }

    /**
     * @return string
     */
    protected function shortClassName()
    {
        $pieces = explode('\\', get_class($this));

        return lcfirst(end($pieces));
    }
}
