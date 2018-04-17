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
 * PropertyExists class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyExists extends Rule
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * PropertyExists constructor.
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
            json_encode($this->propertyName)
        );
    }
}
