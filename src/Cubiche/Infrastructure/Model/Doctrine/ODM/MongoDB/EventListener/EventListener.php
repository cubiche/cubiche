<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver;
use Cubiche\Infrastructure\Model\Doctrine\ODM\MongoDB\Types\DynamicNativeValueObjectType;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
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
        $this->checkValueObjectType($eventArgs->getClassMetadata());
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    protected function checkValueObjectType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['cubiche:valueobject'])) {
                $valueMapping = $mapping['cubiche:valueobject'];

                $type = str_replace('\\', '.', $valueMapping['type']);
                if (!Type::hasType($type)) {
                    Type::registerType($type, DynamicNativeValueObjectType::class);
                    Type::getType($type)->setTargetClass($valueMapping['type']);
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }
}
