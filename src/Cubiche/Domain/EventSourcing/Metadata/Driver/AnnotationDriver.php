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
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new ClassMetadata($class->getName());

        $classAnnotations = $this->reader->getClassAnnotations($class);
        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof Migratable) {
                $classMetadata->setIsMigratable(true);
            }
        }

        return $classMetadata;
    }
}
