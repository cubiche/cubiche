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

use Cubiche\Infrastructure\Collections\Doctrine\ODM\MongoDB\Mapping\PropertyMetadata;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event\RegisterDriverMetadataEventArgs;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\Driver\DriverFactory;
use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Events;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;
use Metadata\MetadataFactory;

/**
 * MetadataEventListener class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MetadataEventListener
{
    /**
     * @var MetadataFactory
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
            )
            ->getMetadataForClass($classMetadata->getName())
        ;

        foreach ($classMetadata->fieldMappings as $fieldName => &$mapping) {
            if (isset($cubicheClassMetadata->propertyMetadata[$fieldName])) {
                /** @var PropertyMetadata $propertyMetadata */
                $propertyMetadata = $cubicheClassMetadata->propertyMetadata[$fieldName];

                $mapping['cubiche:'.$propertyMetadata->namespace] = $propertyMetadata->toArray();
            }
        }
    }

    /**
     * @param ObjectManager $om
     *
     * @return MetadataFactory
     */
    protected function getMetadataFactory(ObjectManager $om, EventManager $evm)
    {
        if ($this->metadataFactory === null) {
            $driverFactory = DriverFactory::instance();

            if ($evm->hasListeners(Events::REGISTER_DRIVER_METADATA)) {
                $eventArgs = new RegisterDriverMetadataEventArgs($driverFactory, $om);
                $evm->dispatchEvent(Events::REGISTER_DRIVER_METADATA, $eventArgs);
            }

            $this->metadataFactory = new MetadataFactory($driverFactory->driversFromManager($om));
        }

        return $this->metadataFactory;
    }
}
