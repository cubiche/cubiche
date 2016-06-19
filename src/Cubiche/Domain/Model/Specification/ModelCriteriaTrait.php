<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\Specification;

use Cubiche\Domain\Model\Selector\Entity;

/**
 * Model Criteria Trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait ModelCriteriaTrait
{
    /**
     * @var Entity
     */
    protected static $entity = null;

    /**
     * @return \Cubiche\Domain\Model\Selector\Entity
     */
    public static function asEntity()
    {
        if (self::$entity === null) {
            self::$entity = new Entity();
        }

        return self::$entity;
    }
}
