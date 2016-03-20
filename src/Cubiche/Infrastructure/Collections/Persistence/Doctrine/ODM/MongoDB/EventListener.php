<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Collections\Persistence\Doctrine\ODM\MongoDB;

use Cubiche\Infrastructure\Collections\Persistence\Doctrine\Common\Collections\PersistentArrayCollection;
use Cubiche\Infrastructure\Collections\Persistence\Doctrine\ODM\MongoDB\Types\ArrayCollectionType;
use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\Types\Type;

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
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->replacePersistentCollections($eventArgs->getDocument(), $eventArgs->getDocumentManager());
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function postLoadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $this->checkArrayCollectionType($eventArgs->getClassMetadata());
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

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    protected function checkArrayCollectionType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if ($mapping['type'] === 'ArrayCollection') {
                if (isset($mapping['innerType'])) {
                    $type = $mapping['innerType'].'ArrayCollection';
                    if (!Type::hasType($type)) {
                        Type::addType($type, ArrayCollectionType::class);
                        Type::getType($type)->setInnerType($mapping['innerType']);
                    }
                    $classMetadata->fieldMappings[$fieldName]['type'] = $type;
                    unset($classMetadata->fieldMappings[$fieldName]['innerType']);
                } else {
                    throw new MappingException(\sprintf(
                        'The innerType option of ArrayCollection type is missing in %s::%s mapping.',
                        $classMetadata->name,
                        $fieldName
                    ));
                }
            }
        }
    }
}
