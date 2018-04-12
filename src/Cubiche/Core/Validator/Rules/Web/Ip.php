<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Web;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Ip class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Ip extends Rule
{
    /**
     * @var string|null
     */
    protected $flag;

    /**
     * Ip constructor.
     *
     * @param string|null $flag
     */
    public function __construct($flag = null)
    {
        $this->flag = $flag;

        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function flag()
    {
        return $this->flag;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->flag
        );
    }
}
