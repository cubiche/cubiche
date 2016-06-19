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

use Cubiche\Core\Validator\Assert;
use Metadata\PropertyMetadata as BasePropertyMetadata;

/**
 * PropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyMetadata extends BasePropertyMetadata
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
    public function getClassName()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDefaultGroup()
    {
        return $this->defaultGroup;
    }

    /**
     * @param string $group
     *
     * @return string
     */
    protected function getGroup($group = null)
    {
        return $group === null || empty($group) ? $this->defaultGroup : $group;
    }

    /**
     * @param Assert $constraint
     * @param string $group
     */
    public function addConstraint(Assert $constraint, $group = null)
    {
        $group = $this->getGroup($group);
        if (!isset($this->constraintsByGroup[$group])) {
            $this->constraintsByGroup[$group] = array();
        }

        $this->constraintsByGroup[$group][] = $constraint;
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
    public function getConstraints()
    {
        return $this->constraintsByGroup;
    }

    /**
     * @param $group
     *
     * @return array
     */
    public function getConstraintsByGroup($group)
    {
        return isset($this->constraintsByGroup[$group]) ? $this->constraintsByGroup[$group] : array();
    }

    /**
     * Merges the constraints of the given metadata into this object.
     *
     * @param PropertyMetadata $source
     */
    public function mergeConstraints(PropertyMetadata $source)
    {
        foreach ($source->getConstraints() as $group => $constraints) {
            $this->addConstraints($constraints, $group);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->class,
            $this->name,
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
            $this->class,
            $this->name,
            $this->defaultGroup,
            $this->constraintsByGroup) = unserialize($str);

        $this->reflection = new \ReflectionProperty($this->class, $this->name);
        $this->reflection->setAccessible(true);
    }
}
