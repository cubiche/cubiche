<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Event;

use Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Mapping\Driver\DriverFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\Event\ManagerEventArgs;

/**
 * RegisterDriverMetadataEventArgs class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterDriverMetadataEventArgs extends ManagerEventArgs
{
    /**
     * @var DriverFactory
     */
    protected $driverFactory;

    /**
     * RegisterDriverMetadataEventArgs constructor.
     *
     * @param DriverFactory $factory
     * @param ObjectManager $objectManager
     */
    public function __construct(DriverFactory $driverFactory, ObjectManager $objectManager)
    {
        parent::__construct($objectManager);

        $this->driverFactory = $driverFactory;
    }

    /**
     * @return DriverFactory
     */
    public function driverFactory()
    {
        return $this->driverFactory;
    }
}
