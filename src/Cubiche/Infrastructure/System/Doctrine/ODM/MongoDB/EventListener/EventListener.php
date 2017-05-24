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

use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\DecimalType;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\DynamicEnumType;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\IntegerType;
use Cubiche\Infrastructure\System\Doctrine\ODM\MongoDB\Types\RealType;
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
     * @var array
     */
    protected $typeMapping = array(
        'decimal' => DecimalType::class,
        'integer' => IntegerType::class,
        'real' => RealType::class,
        'string' => StringLiteralType::class,
    );

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
        $this->checkTypes($eventArgs->getClassMetadata());
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
                /** @var PropertyMetadata $propertyMetadata */
                $propertyMetadata = $mapping['cubiche:enum'];

                $type = str_replace('\\', '.', $propertyMetadata->getMetadata('type'));
                if (!Type::hasType($type)) {
                    Type::registerType($type, DynamicEnumType::class);
                    Type::getType($type)->setTargetClass($propertyMetadata->getMetadata('type'));
                }

                $classMetadata->fieldMappings[$fieldName]['type'] = $type;
            }
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    protected function checkTypes(ClassMetadata $classMetadata)
    {
        $types = array_keys($this->typeMapping);
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            foreach ($types as $type) {
                if (isset($mapping['cubiche:'.$type])) {
                    $typeName = substr(
                        $this->typeMapping[$type],
                        strrpos($this->typeMapping[$type], '\\') + 1
                    );

                    if (!Type::hasType($typeName)) {
                        Type::registerType($typeName, $this->typeMapping[$type]);
                    }

                    $classMetadata->fieldMappings[$fieldName]['type'] = $typeName;
                    break;
                }
            }
        }
    }
}
