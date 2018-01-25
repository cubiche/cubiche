<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\MongoDB\Metadata\Driver;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Driver\AbstractAnnotationDriver;
use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Infrastructure\MongoDB\Metadata\Annotations\AggregateRoot;
use Cubiche\Infrastructure\MongoDB\Metadata\Annotations\Entity;
use Cubiche\Infrastructure\MongoDB\Metadata\Annotations\Field;
use Cubiche\Infrastructure\MongoDB\Metadata\Annotations\Id;

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
        try {
            $hasMetadata = false;
            $reflectionClass = new \ReflectionClass($className);

            foreach ($this->reader->getClassAnnotations($reflectionClass) as $annotation) {
                if ($annotation instanceof AggregateRoot || $annotation instanceof Entity) {
                    $hasMetadata = true;
                }
            }

            if ($hasMetadata) {
                $classMetadata = new ClassMetadata($className);
                foreach ($classMetadata->reflection()->getProperties() as $propertyReflection) {
                    foreach ($this->reader->getPropertyAnnotations($propertyReflection) as $annotation) {
                        $name = $propertyReflection->getName();
                        $fieldName = $propertyReflection->getName();

                        if ($annotation instanceof Field) {
                            if (!empty($annotation->name)) {
                                $name = $annotation->name;
                            }

                            $propertyMetadata = new PropertyMetadata(
                                $classMetadata->className(),
                                $fieldName
                            );

                            $propertyMetadata->addMetadata('identifier', $annotation->id);
                            $propertyMetadata->addMetadata('name', $name);

                            if (!empty($annotation->type)) {
                                $propertyMetadata->addMetadata('type', $annotation->type);
                            }

                            $classMetadata->addPropertyMetadata($propertyMetadata);
                        } elseif ($annotation instanceof Id) {
                            $propertyMetadata = new PropertyMetadata(
                                $classMetadata->className(),
                                $fieldName
                            );

                            $propertyMetadata->addMetadata('identifier', true);
                            $propertyMetadata->addMetadata('name', '_id');

                            if (!empty($annotation->type)) {
                                $propertyMetadata->addMetadata('type', $annotation->type);
                            }

                            $classMetadata->addPropertyMetadata($propertyMetadata);
                        }
                    }
                }

                return $classMetadata;
            }

            return;
        } catch (\ReflectionException $e) {
            throw MappingException::classNotFound($className);
        }
    }
}
