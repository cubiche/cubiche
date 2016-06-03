<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Mapping;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\PropertyMetadata;

/**
 * CollectionPropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CollectionPropertyMetadata extends PropertyMetadata
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $typeClassName;

    /**
     * @var string
     */
    public $persistenClassName;

    /**
     * @var string
     */
    public $of;

    /**
     * @var string
     */
    public $namespace;

    /**
     * CollectionPropertyMetadata constructor.
     *
     * @param string $class
     * @param string $name
     */
    public function __construct($class, $name)
    {
        parent::__construct($class, $name, 'collection');
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'type' => $this->type,
            'typeClassName' => $this->typeClassName,
            'persistenClassName' => $this->persistenClassName,
            'of' => $this->of,
        );
    }
}
