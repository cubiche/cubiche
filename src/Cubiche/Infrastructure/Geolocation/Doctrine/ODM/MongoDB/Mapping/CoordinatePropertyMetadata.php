<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Geolocation\Doctrine\ODM\MongoDB\Mapping;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\PropertyMetadata;

/**
 * CoordinatePropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CoordinatePropertyMetadata extends PropertyMetadata
{
    /**
     * IdentifyPropertyMetadata constructor.
     *
     * @param string $class
     * @param string $name
     */
    public function __construct($class, $name)
    {
        parent::__construct($class, $name, 'coordinate');
    }
}
