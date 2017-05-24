<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Driver;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Driver\AbstractAnnotationDriver;
use Cubiche\Core\Metadata\Exception\MappingException;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations\AggregateRoot;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations\Entity;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations\Field;
use Cubiche\Core\Metadata\Tests\Fixtures\Annotations\Id;

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
                if ($annotation instanceof AggregateRoot) {
                    $hasMetadata = true;
                } elseif ($annotation instanceof Entity) {
                    $hasMetadata = true;
                }
            }

            if ($hasMetadata) {
                $classMetadata = new ClassMetadata($className);
                foreach ($classMetadata->reflection()->getProperties() as $propertyReflection) {
                    foreach ($this->reader->getPropertyAnnotations($propertyReflection) as $annotation) {
                        if ($annotation instanceof Field) {
                            $propertyMetadata = new PropertyMetadata(
                                $classMetadata->className(),
                                $propertyReflection->getName()
                            );

                            $propertyMetadata->addMetadata('identifier', $annotation->id);
                            if (!empty($annotation->name)) {
                                $propertyMetadata->addMetadata('name', $annotation->name);
                            }

                            if (!empty($annotation->type)) {
                                $propertyMetadata->addMetadata('type', $annotation->type);
                                if (!empty($annotation->type)) {
                                    $propertyMetadata->addMetadata('of', $annotation->of);
                                }
                            }

                            $classMetadata->addPropertyMetadata($propertyMetadata);
                        } elseif ($annotation instanceof Id) {
                            $propertyMetadata = new PropertyMetadata(
                                $classMetadata->className(),
                                $propertyReflection->getName()
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
