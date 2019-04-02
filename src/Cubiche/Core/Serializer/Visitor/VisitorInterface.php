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
use Cubiche\Core\Visitor\VisitorInterface as BaseVisitorInterface;

/**
 * Visitor interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface VisitorInterface extends BaseVisitorInterface
{
    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitNull($data, array $type, ContextInterface $context);

    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitString($data, array $type, ContextInterface $context);

    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitBoolean($data, array $type, ContextInterface $context);

    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitInteger($data, array $type, ContextInterface $context);

    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitDouble($data, array $type, ContextInterface $context);

    /**
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitArray($data, array $type, ContextInterface $context);

    /**
     * @param ClassMetadata    $classMetadata
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function startVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context);

    /**
     * @param ClassMetadata    $classMetadata
     * @param mixed            $data
     * @param array            $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function endVisitingObject(ClassMetadata $classMetadata, $data, array $type, ContextInterface $context);

    /**
     * @param PropertyMetadata $propertyMetadata
     * @param mixed            $data
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function visitProperty(PropertyMetadata $propertyMetadata, $data, ContextInterface $context);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function isNull($value);
}
