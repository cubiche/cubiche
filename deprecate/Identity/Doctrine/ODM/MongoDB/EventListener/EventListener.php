<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver;
use Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Types\DynamicIdType;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Event Listener Class.
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
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function postLoadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $this->checkIdType($eventArgs->getClassMetadata());
    }

    /**
     * @param ClassMetadata $classMetadata
     *
     * @throws MappingException
     */
    protected function checkIdType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['cubiche:id'])) {
                /** @var PropertyMetadata $propertyMetadata */
                $propertyMetadata = $mapping['cubiche:id'];

                $type = str_replace('\\', '.', $propertyMetadata->getMetadata('type'));
                if (!Type::hasType($type)) {
                    Type::registerType($type, DynamicIdType::class);
                    Type::getType($type)->setTargetClass($propertyMetadata->getMetadata('type'));
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }
}
