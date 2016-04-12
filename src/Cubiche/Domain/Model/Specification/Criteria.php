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

use Cubiche\Core\Specification\Criteria as BaseCriteria;
use Cubiche\Domain\Model\Selector\Entity;

/**
 * Criteria Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Criteria extends BaseCriteria
{
    /**
     * @var Entity
     */
    protected static $entity = null;

    /**
     * @return \Cubiche\Domain\Model\Specification\Selector\Entity
     */
    public static function entity()
    {
        if (self::$entity === null) {
            self::$entity = new Entity();
        }

        return self::$entity;
    }
}
