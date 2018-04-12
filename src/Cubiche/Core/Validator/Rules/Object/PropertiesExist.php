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
 * PropertiesExist class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertiesExist extends Rule
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * PropertiesExist constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;

        parent::__construct();
    }

    /**
     * @return array
     */
    public function properties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        sort($this->properties);

        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            implode(',', $this->properties)
        );
    }
}
