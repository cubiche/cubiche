<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Object;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * MethodNotExists class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodNotExists extends Rule
{
    /**
     * @var string
     */
    protected $methodName;

    /**
     * MethodNotExists constructor.
     *
     * @param string $methodName
     */
    public function __construct($methodName)
    {
        $this->methodName = $methodName;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function methodName()
    {
        return $this->methodName;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->methodName
        );
    }
}
