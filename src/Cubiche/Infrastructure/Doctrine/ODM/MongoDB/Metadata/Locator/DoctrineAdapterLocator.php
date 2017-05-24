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

use Cubiche\Core\Metadata\Locator\FileLocatorInterface;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;

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
     * {@inheritdoc}
     */
    public function findMappingFile($className, $extension)
    {
        $this->locator->setFileExtension($extension);

        try {
            return $this->locator->findMappingFile($className);
        } catch (MappingException $e) {
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames($extension)
    {
        return $this->locator->getAllClassNames(null);
    }
}
