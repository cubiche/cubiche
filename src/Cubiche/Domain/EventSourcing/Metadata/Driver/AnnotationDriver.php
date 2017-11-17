<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Metadata\Driver;

use Cubiche\Core\Metadata\Driver\AbstractAnnotationDriver;
use Cubiche\Domain\EventSourcing\Metadata\Annotations\Migratable;
use Cubiche\Domain\EventSourcing\Metadata\ClassMetadata;

/**
 * AnnotationDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AnnotationDriver extends AbstractAnnotationDriver
{
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass($className)
    {
        $classMetadata = new ClassMetadata($className);

        $classAnnotations = $this->reader->getClassAnnotations($classMetadata->reflection());
        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof Migratable) {
                $classMetadata->setIsMigratable(true);
            }
        }

        return $classMetadata;
    }
}
