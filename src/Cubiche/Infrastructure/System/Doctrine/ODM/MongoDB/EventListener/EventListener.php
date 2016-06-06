<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\DynamicEnumType;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\StringLiteralType;
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
        $this->checkEnumType($eventArgs->getClassMetadata());
        $this->checkStringType($eventArgs->getClassMetadata());
    }

    /**
     * @param ClassMetadata $classMetadata
     *
     * @throws MappingException
     */
    protected function checkEnumType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['cubiche:enum'])) {
                $enumMapping = $mapping['cubiche:enum'];

                $type = str_replace('\\', '.', $enumMapping['type']);
                if (!Type::hasType($type)) {
                    Type::registerType($type, DynamicEnumType::class);
                    Type::getType($type)->setTargetClass($enumMapping['type']);
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    protected function checkStringType(ClassMetadata $classMetadata)
    {
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (isset($mapping['cubiche:string'])) {
                $type = 'StringLiteral';
                if (!Type::hasType($type)) {
                    Type::registerType($type, StringLiteralType::class);
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }
}
