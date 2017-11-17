<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\EventListener;

use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Events;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Factory\DriverFactory;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;

/**
 * MetadataEventListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MetadataEventListener
{
    /**
     * @var ClassMetadataFactory
     */
    protected $metadataFactory;

    /**
     * Add mapping to translatable entities.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflClass = $classMetadata->reflClass;
        if (!$reflClass || $reflClass->isAbstract()) {
            return;
        }

        $cubicheClassMetadata = $this
            ->getMetadataFactory(
                $eventArgs->getDocumentManager(),
                $eventArgs->getDocumentManager()->getEventManager()
            )->getMetadataFor($classMetadata->getName())
        ;

        foreach ($classMetadata->fieldMappings as $fieldName => &$mapping) {
            if ($cubicheClassMetadata !== null) {
                $propertyMetadata = $cubicheClassMetadata->propertyMetadata($fieldName);
                if ($propertyMetadata !== null) {
                    $mapping['cubiche:'.$propertyMetadata->getMetadata('namespace')] = $propertyMetadata;
                }
            }
        }
    }

    /**
     * @param ObjectManager $om
     *
     * @return ClassMetadataFactory
     */
    protected function getMetadataFactory(ObjectManager $om, EventManager $evm)
    {
        if ($this->metadataFactory === null) {
            $driverFactory = DriverFactory::instance();

            if ($evm->hasListeners(Events::REGISTER_DRIVER_METADATA)) {
                $eventArgs = new RegisterDriverMetadataEventArgs($driverFactory, $om);
                $evm->dispatchEvent(Events::REGISTER_DRIVER_METADATA, $eventArgs);
            }

            $this->metadataFactory = new ClassMetadataFactory($driverFactory->driversFromManager($om));
        }

        return $this->metadataFactory;
    }
}
