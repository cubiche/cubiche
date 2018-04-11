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
 * SubclassOf class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SubclassOf extends Rule
{
    /**
     * @var string
     */
    protected $className;

    /**
     * SubclassOf constructor.
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->className
        );
    }
}
