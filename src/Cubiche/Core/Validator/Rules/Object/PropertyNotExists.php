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
 * PropertyNotExists class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyNotExists extends Rule
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * PropertyNotExists constructor.
     *
     * @param string $propertyName
     */
    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function propertyName()
    {
        return $this->propertyName;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->propertyName
        );
    }
}
