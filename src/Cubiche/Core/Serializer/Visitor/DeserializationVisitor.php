<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Visitor;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\PropertyMetadata;
use RuntimeException;

/**
 * DeserializationVisitor class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DeserializationVisitor extends AbstractVisitor
{
    /**
     * @var array
     */
    protected $currentObject;

    /**
     * {@inheritdoc}
     */
    public function visitNull($data, array $type, ContextInterface $context)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function visitString($data, array $type, ContextInterface $context)
    {
        return (string) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, array $type, ContextInterface $context)
    {
        return (bool) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitInteger($data, array $type, ContextInterface $context)
    {
        return (int) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitDouble($data, array $type, ContextInterface $context)
    {
        return (float) $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, array $type, ContextInterface $context)
    {
        if (!\is_array($data)) {
            throw new RuntimeException(sprintf('Expected array, but got %s: %s', \gettype($data), json_encode($data)));
        }

        // If no further parameters were given, keys/values are just passed as is.
        if (!$type['params']) {
            return $data;
        }

        switch (\count($type['params'])) {
            case 1: // Array is a list.
                $listType = $type['params'][0];
                $result = array();
                foreach ($data as $value) {
                    $result[] = $this->navigator->accept($value, $listType, $context);
                }

                return $result;
            case 2: // Array is a map.
                list($keyType, $entryType) = $type['params'];
                $result = array();
                foreach ($data as $key => $value) {
                    $keyName = $this->navigator->accept($key, $keyType, $context);

                    $result[$keyName] = $this->navigator->accept($value, $entryType, $context);
                }

                return $result;
            default:
                throw new RuntimeException(
                    sprintf('Array type cannot have more than 2 parameters, but got %s.', json_encode($type['params']))
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function visitHashmap($data, array $type, ContextInterface $context)
    {
        if (!\is_array($data)) {
            throw new RuntimeException(sprintf('Expected array, but got %s: %s', \gettype($data), json_encode($data)));
        }

        $result = array();
        foreach ($data as $key => $value) {
            if (isset($type['params'][$key])) {
                $result[$key] = $this->navigator->accept($value, $type['params'][$key], $context);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function startVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context)
    {
        $this->currentObject = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function endVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context)
    {
        return $this->currentObject;
    }

    /**
     * {@inheritdoc}
     */
    public function visitProperty(PropertyMetadata $propertyMetadata, $data, ContextInterface $context)
    {
        $propertyName = $propertyMetadata->propertyName();
        if ($propertyMetadata->getMetadata('name') !== null) {
            $propertyName = $propertyMetadata->getMetadata('name');
        }

        if (null === $data) {
            return;
        }

        if ($propertyMetadata->getMetadata('type') === null) {
            throw new RuntimeException(
                sprintf(
                    'You must define a type for %s::$%s.',
                    $propertyMetadata->className(),
                    $propertyMetadata->propertyName()
                )
            );
        }

        if (!\is_array($data)) {
            throw new RuntimeException(
                sprintf(
                    'Invalid data "%s"(%s), expected "%s".',
                    $data,
                    $propertyMetadata->getMetadata('type'),
                    $propertyMetadata->className()
                )
            );
        }

        if (!array_key_exists($propertyName, $data)) {
            return;
        }

        $value = null;
        if ($data[$propertyName] !== null) {
            $type = array('name' => $propertyMetadata->getMetadata('type'), 'params' => array());
            // We have to persist the current object we have so far to prevent a recursive call from overwriting it
            $objectSoFar = $this->currentObject;
            $value = $this->navigator->accept($data[$propertyName], $type, $context);
            $this->currentObject = $objectSoFar;
        }

        $propertyMetadata->setValue($this->currentObject, $value);
    }
}
