<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata;

/**
 * ClassMetadata interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface ClassMetadataInterface
{
    /**
     * @return string
     */
    public function className();

    /**
     * @return \ReflectionClass
     */
    public function reflection();

    /**
     * @return array
     */
    public function methodsMetadata();

    /**
     * @param MethodMetadata $metadata
     */
    public function addMethodMetadata(MethodMetadata $metadata);

    /**
     * @param string $methodName
     *
     * @return MethodMetadata|null
     */
    public function methodMetadata($methodName);

    /**
     * @return array
     */
    public function propertiesMetadata();

    /**
     * @param PropertyMetadata $metadata
     */
    public function addPropertyMetadata(PropertyMetadata $metadata);

    /**
     * @param string $propertyName
     *
     * @return PropertyMetadata|null
     */
    public function propertyMetadata($propertyName);

    /**
     * @return array
     */
    public function metadata();

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function addMetadata($key, $value);

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getMetadata($key);

    /**
     * @param ClassMetadataInterface $object
     *
     * @return ClassMetadataInterface
     */
    public function merge(ClassMetadataInterface $object);
}
