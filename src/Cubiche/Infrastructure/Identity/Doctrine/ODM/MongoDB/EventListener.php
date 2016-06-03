<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB;

use Cubiche\Infrastructure\Identity\Doctrine\ODM\MongoDB\Types\DynamicIdType;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
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
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function postLoadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ODM\MongoDB\Mapping\ClassMetadata $classMetadata */
        $classMetadata = $eventArgs->getClassMetadata();
        foreach ($classMetadata->fieldMappings as $fieldName => $mapping) {
            if (!isset($mapping['type'])) {
                continue;
            }

            if ($mapping['type'] === 'Identifier') {
                if (isset($mapping['target'])) {
                    $type = str_replace('\\', '.', $mapping['target']);
                    if (!Type::hasType($type)) {
                        Type::registerType($type, DynamicIdType::class);
                        Type::getType($type)->setTargetClass($mapping['target']);
                    }
                    $classMetadata->fieldMappings[$fieldName]['type'] = $type;
                    unset($classMetadata->fieldMappings[$fieldName]['target']);
                } else {
                    throw new MappingException(\sprintf(
                        'The target option of Identifier type is missing in %s::%s mapping.',
                        $classMetadata->name,
                        $fieldName
                    ));
                }
            }
        }
    }
}
