<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Core\Collection\CollectionInterface;
use Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\PersistentCollection;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * EventListener class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventListener
{
    /**
     * @param RegisterDriverMetadataEventArgs $eventArgs
     */
    public function registerDriverMetadata(RegisterDriverMetadataEventArgs $eventArgs)
    {
        $eventArgs->driverFactory()->registerXmlDriver(XmlDriver::class);
    }

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
                && $value !== null && !($value instanceof PersistentCollection)
            ) {
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

            if ((isset($mapping['association']) && $mapping['type'] === 'many') && $value !== null) {
                if (isset($mapping['cubiche:collection'])) {
                    $value = new $mapping['cubiche:collection']['persistenClassName']($value);
                    if ($reflectionProperty->isPrivate()) {
                        $reflectionProperty->setAccessible(true);
                    }

                    $reflectionProperty->setValue($document, $value);
                }
            }
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     *
     * @throws MappingException
     */
    protected function checkArrayCollectionType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['embedded']) || isset($mapping['reference'])) {
                continue;
            }

            if (isset($mapping['cubiche:collection'])) {
                $collectionMapping = $mapping['cubiche:collection'];
                if ($collectionMapping['of'] === null) {
                    throw MappingException::inField(
                        'The "of" option in '.$collectionMapping['type'].' type is missing',
                        $classMetadata->name,
                        $fieldName
                    );
                }

                $type = $collectionMapping['of'].$collectionMapping['type'];
                if (!Type::hasType($type)) {
                    Type::addType($type, $collectionMapping['typeClassName']);
                    Type::getType($type)->setInnerType($collectionMapping['of']);
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }
}
