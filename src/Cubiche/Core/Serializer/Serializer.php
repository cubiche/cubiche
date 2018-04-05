<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer;

use Cubiche\Core\Serializer\Context\ContextInterface;
use Cubiche\Core\Serializer\Context\DeserializationContext;
use Cubiche\Core\Serializer\Context\SerializationContext;
use Cubiche\Core\Serializer\Visitor\VisitorInterface;
use Cubiche\Core\Serializer\Visitor\VisitorNavigatorInterface;

/**
 * Serializer class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var VisitorNavigatorInterface
     */
    protected $navigator;

    /**
     * @var VisitorInterface
     */
    protected $serializationVisitor;

    /**
     * @var VisitorInterface
     */
    protected $deserializationVisitor;

    /**
     * Serializer constructor.
     *
     * @param VisitorNavigatorInterface $navigator
     * @param VisitorInterface          $serializationVisitor
     * @param VisitorInterface          $deserializationVisitor
     */
    public function __construct(
        VisitorNavigatorInterface $navigator,
        VisitorInterface $serializationVisitor,
        VisitorInterface $deserializationVisitor
    ) {
        $this->navigator = $navigator;
        $this->serializationVisitor = $serializationVisitor;
        $this->deserializationVisitor = $deserializationVisitor;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data, ContextInterface $context = null)
    {
        if (null === $context) {
            $context = new SerializationContext($this->serializationVisitor, $this->navigator);
        }

        $typeName = is_object($data) ? get_class($data) : gettype($data);
        $type = array('name' => $typeName, 'params' => array());

        return $this->navigator->accept($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $type, ContextInterface $context = null)
    {
        if (null === $context) {
            $context = new DeserializationContext($this->deserializationVisitor, $this->navigator);
        }

        $type = array('name' => $type, 'params' => array());

        return $this->navigator->accept($data, $type, $context);
    }
}
