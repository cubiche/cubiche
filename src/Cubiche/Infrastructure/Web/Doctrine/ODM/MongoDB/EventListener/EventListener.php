<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Metadata\Driver\XmlDriver;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\EmailAddressType;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\HostNameType;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\IPAddressType;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\PathType;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\PortType;
use Cubiche\Infrastructure\Web\Doctrine\ODM\MongoDB\Types\UrlType;
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
     * @var array
     */
    protected $typeMapping = array(
        'email' => EmailAddressType::class,
        'hostname' => HostNameType::class,
        'ip' => IPAddressType::class,
        'path' => PathType::class,
        'port' => PortType::class,
        'url' => UrlType::class,
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
        $this->checkTypes($eventArgs->getClassMetadata());
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
