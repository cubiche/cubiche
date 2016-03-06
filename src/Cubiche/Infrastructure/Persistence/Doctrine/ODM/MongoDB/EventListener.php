<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Collections\CollectionInterface;
use Cubiche\Infrastructure\Persistence\Doctrine\Common\Collections\PersistentArrayCollection;
use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Event\OnFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PostFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\Event\PreLoadEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * Event Listener Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class EventListener
{
    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->replaceCollections($eventArgs->getDocument(), $eventArgs->getDocumentManager());
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->replacePersistentCollections($eventArgs->getDocument(), $eventArgs->getDocumentManager());
    }

    /**
     * @param PreUpdateEventArgs $eventArgs
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
    }

    /**
     * @param PreLoadEventArgs $eventArgs
     */
    public function preLoad(PreLoadEventArgs $eventArgs)
    {
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->replacePersistentCollections($eventArgs->getDocument(), $eventArgs->getDocumentManager());
    }

    /**
     * @param PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
    }

    /**
     * @param object          $document
     * @param DocumentManager $dm
     */
    protected function replaceCollections($document, DocumentManager $dm)
    {
        $classMetadata = $dm->getClassMetadata(\get_class($document));
        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($classMetadata->getReflectionProperties() as $propertyName => $reflectionProperty) {
            $mapping = $classMetadata->getFieldMapping($propertyName);
            $value = $reflectionProperty->getValue($document);
            if ((isset($mapping['association']) && $mapping['type'] === 'many')
                && $value !== null && !($value instanceof PersistentCollection)) {
                if ($value instanceof CollectionInterface) {
                    $value = new DoctrineArrayCollection($value->toArray());
                    $reflectionProperty->setValue($document, $value);
                }
            }
        }
    }

    /**
     * @param object          $document
     * @param DocumentManager $dm
     */
    protected function replacePersistentCollections($document, DocumentManager $dm)
    {
        $classMetadata = $dm->getClassMetadata(\get_class($document));
        /** @var \ReflectionProperty $reflectionProperty */
        foreach ($classMetadata->getReflectionProperties() as $propertyName => $reflectionProperty) {
            $mapping = $classMetadata->getFieldMapping($propertyName);
            $value = $reflectionProperty->getValue($document);
            if ((isset($mapping['association']) && $mapping['type'] === 'many')
                && $value !== null && !($value instanceof PersistentArrayCollection)) {
                $value = new PersistentArrayCollection($value);
                $reflectionProperty->setValue($document, $value);
            }
        }
    }
}
