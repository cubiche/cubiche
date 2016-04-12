<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB;

use Doctrine\ODM\MongoDB\Events as BaseEvents;

/**
 * Events Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class Events
{
    private function __construct()
    {
    }

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::preRemove
     */
    const PRE_REMOVE = BaseEvents::preRemove;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::postRemove
     */
    const POST_REMOVE = BaseEvents::postRemove;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::prePersist
     */
    const PRE_PERSIST = BaseEvents::prePersist;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::postPersist
     */
    const POST_PERSIST = BaseEvents::postPersist;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::preUpdate
     */
    const PRE_UPDATE = BaseEvents::preUpdate;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::postUpdate
     */
    const POST_UPDATE = BaseEvents::postUpdate;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::preLoad
     */
    const PRE_LOAD = BaseEvents::preLoad;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::postLoad
     */
    const POST_LOAD = BaseEvents::postLoad;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::loadClassMetadata
     */
    const PRE_LOAD_CLASSMETADATA = 'preLoadClassMetadata';

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::loadClassMetadata
     */
    const LOAD_CLASS_METADATA = BaseEvents::loadClassMetadata;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::loadClassMetadata
     */
    const POST_LOAD_CLASS_METADATA = 'postLoadClassMetadata';

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::preFlush
     */
    const PRE_FLUSH = BaseEvents::preFlush;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::onFlush
     */
    const ON_FLUSH = BaseEvents::onFlush;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::postFlush
     */
    const POST_FLUSH = BaseEvents::postFlush;

    /**
     * @var string
     *
     * @see \Doctrine\ODM\MongoDB\Events::onClear
     */
    const ON_CLEAR = BaseEvents::onClear;
}
