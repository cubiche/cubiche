<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Mapping;

use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Metadata\MethodMetadata as BaseMethodMetadata;
use Cubiche\Core\Validator\Assertion;

/**
 * MethodMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MethodMetadata extends BaseMethodMetadata
{
    /**
     * @var string
     */
    public $defaultGroup;

    /**
     * @var array
     */
    public $constraintsByGroup = array();

    /**
     * @return string
     */
    public function defaultGroup()
    {
        return $this->defaultGroup;
    }

    /**
     * @param string $group
     *
     * @return string
     */
    protected function group($group = null)
    {
        return $group === null || empty($group) ? $this->defaultGroup : $group;
    }

    /**
     * @param Assertion $constraint
     * @param string    $group
     */
    public function addConstraint(Assertion $constraint, $group = null)
    {
        $group = $this->group($group);
        if (!isset($this->constraintsByGroup[$group])) {
            $this->constraintsByGroup[$group] = array();
        }

        if (!isset($this->constraintsByGroup[$group][$constraint->id()])) {
            $this->constraintsByGroup[$group][$constraint->id()] = $constraint;
        }
    }

    /**
     * @param array  $constraints
     * @param string $group
     */
    public function addConstraints(array $constraints, $group = null)
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint, $group);
        }
    }

    /**
     * @return array
     */
    public function constraints()
    {
        return $this->constraintsByGroup;
    }

    /**
     * @param $group
     *
     * @return array
     */
    public function constraintsByGroup($group)
    {
        return isset($this->constraintsByGroup[$group]) ? $this->constraintsByGroup[$group] : array();
    }

    /**
     * Merges the constraints of the given metadata into this object.
     *
     * @param MethodMetadata $source
     */
    public function mergeConstraints(MethodMetadata $source)
    {
        foreach ($source->constraints() as $group => $constraints) {
            $this->addConstraints($constraints, $group);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->className,
            $this->methodName,
            $this->metadata->toArray(),
            $this->defaultGroup,
            $this->constraintsByGroup,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->className,
            $this->methodName,
            $metadata,
            $this->defaultGroup,
            $this->constraintsByGroup
        ) = unserialize($str);

        $this->reflection = new \ReflectionMethod($this->className, $this->methodName);
        $this->reflection->setAccessible(true);

        $this->metadata = new ArrayHashMap($metadata);
    }
}
