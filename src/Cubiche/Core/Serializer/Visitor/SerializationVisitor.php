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

/**
 * SerializationVisitor class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class SerializationVisitor extends AbstractVisitor
{
    /**
     * @var array
     */
    protected $data;

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
        $result = array();
        $isList = isset($type['params'][0]) && !isset($type['params'][1]);
        foreach ($data as $key => $value) {
            $value = $this->navigator->accept($value, $this->getElementType($type), $context);
            if ($isList) {
                $result[] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function startVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context)
    {
        $this->data = array();
    }

    /**
     * {@inheritdoc}
     */
    public function endVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context)
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitProperty(PropertyMetadata $propertyMetadata, $data, ContextInterface $context)
    {
        $value = $propertyMetadata->getValue($data);
        $type = array('name' => $propertyMetadata->getMetadata('type'), 'params' => array());
        $value = $this->navigator->accept($value, $type, $context);

        $propertyName = $propertyMetadata->propertyName();
        if ($propertyMetadata->getMetadata('name') !== null) {
            $propertyName = $propertyMetadata->getMetadata('name');
        }

        $this->data[$propertyName] = $value;
    }

    /**
     * @param array $typeArray
     *
     * @return mixed
     */
    protected function getElementType($typeArray)
    {
        if (false === isset($typeArray['params'][0])) {
            return null;
        }

        if (isset($typeArray['params'][1]) && \is_array($typeArray['params'][1])) {
            return $typeArray['params'][1];
        } else {
            return $typeArray['params'][0];
        }
    }
}
