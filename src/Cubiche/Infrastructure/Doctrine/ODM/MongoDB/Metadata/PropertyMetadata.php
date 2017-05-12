<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata;

use Cubiche\Core\Metadata\PropertyMetadata as BasePropertyMetadata;

/**
 * PropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyMetadata extends BasePropertyMetadata
{
    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $type;

    /**
     * PropertyMetadata constructor.
     *
     * @param string $class
     * @param string $name
     * @param string $namespace
     */
    public function __construct($class, $name, $namespace)
    {
        parent::__construct($class, $name);

        $this->namespace = $namespace;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'type' => $this->type,
        );
    }
}
