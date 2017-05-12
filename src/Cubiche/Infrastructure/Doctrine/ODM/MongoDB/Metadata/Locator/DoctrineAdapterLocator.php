<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Doctrine\ODM\MongoDB\Metadata\Locator;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use Metadata\Driver\FileLocatorInterface;

/**
 * DoctrineAdapterLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DoctrineAdapterLocator implements FileLocatorInterface
{
    /**
     * @var FileLocator
     */
    protected $locator;

    /**
     * DoctrineAdapterLocator constructor.
     *
     * @param FileLocator $locator
     */
    public function __construct(FileLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param \ReflectionClass $class
     * @param string           $extension
     *
     * @return string|null
     */
    public function findFileForClass(\ReflectionClass $class, $extension)
    {
        $this->locator->setFileExtension($extension);

        try {
            return $this->locator->findMappingFile($class->getName());
        } catch (MappingException $e) {
            return;
        }
    }
}
