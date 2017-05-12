<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Metadata;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\PropertyMetadata;

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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;

        $this->typeClassName = sprintf(
            'Cubiche\\Infrastructure\\Collections\\Doctrine\\ODM\\MongoDB\\Types\\%sType',
            $type
        );

        $this->persistenClassName = sprintf(
            'Cubiche\\Infrastructure\\Collections\\Doctrine\\Common\\Collections\\Persistent%s',
            $type
        );
    }

    /**
     * @param string $of
     */
    public function setOf($of)
    {
        $this->of = $of;
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
