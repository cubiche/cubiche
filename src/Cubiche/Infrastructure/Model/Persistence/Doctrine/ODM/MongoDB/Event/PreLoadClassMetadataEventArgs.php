<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Persistence\Doctrine\ODM\MongoDB\Event;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\Event\ManagerEventArgs;

/**
 * Pre-load ClassMetadata Event Arguments Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class PreLoadClassMetadataEventArgs extends ManagerEventArgs
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @param string        $className
     * @param ObjectManager $objectManager
     */
    public function __construct($className, ObjectManager $objectManager)
    {
        parent::__construct($objectManager);

        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}
