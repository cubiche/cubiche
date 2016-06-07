<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Validator\Mapping\Driver;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Core\Validator\Mapping\Exception\MappingException;
use Metadata\Driver\DriverInterface;

/**
 * StaticDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class StaticDriver implements DriverInterface
{
    /**
     * The name of the method to call.
     *
     * @var string
     */
    protected $methodName;

    /**
     * @var string
     */
    protected $defaultGroup;

    /**
     * StaticDriver constructor.
     *
     * @param string $methodName
     * @param string $defaultGroup
     */
    public function __construct($methodName = 'loadValidatorMetadata', $defaultGroup = Assert::DEFAULT_GROUP)
    {
        $this->methodName = $methodName;
        $this->defaultGroup = $defaultGroup;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $reflClass)
    {
        if (!$reflClass->isInterface() && $reflClass->hasMethod($this->methodName)) {
            $reflMethod = $reflClass->getMethod($this->methodName);

            if ($reflMethod->isAbstract()) {
                throw MappingException::withMessage(
                    'The class %s should not be and abstract class',
                    $reflClass->name,
                    $this->methodName
                );
            }

            if (!$reflMethod->isStatic()) {
                throw MappingException::withMessage(
                    'The method %s::%s should be static',
                    $reflClass->name,
                    $this->methodName
                );
            }

            if ($reflMethod->getDeclaringClass()->name != $reflClass->name) {
                throw MappingException::withMessage(
                    'The method %s should be declared in %s class',
                    $this->methodName,
                    $reflClass->name
                );
            }

            $metadata = new ClassMetadata($reflClass->getName());
            $metadata->defaultGroup = $this->defaultGroup;

            $reflMethod->invoke(null, $metadata);

            return $metadata;
        }
    }
}
