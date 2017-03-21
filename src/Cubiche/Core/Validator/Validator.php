<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator;

use Cubiche\Core\Validator\Exception\ValidationException;
use Cubiche\Core\Validator\Mapping\ClassMetadata;
use Cubiche\Core\Validator\Mapping\Driver\StaticDriver;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Validator implements ValidatorInterface
{
    /**
     * @var array
     */
    protected $constraints = array();

    /**
     * @var string
     */
    protected $defaultGroup;

    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var Validator
     */
    private static $instance = null;

    /**
     * Validator constructor.
     *
     * @param MetadataFactoryInterface $metadataFactory
     * @param string                   $defaultGroup
     */
    private function __construct(MetadataFactoryInterface $metadataFactory, $defaultGroup = Assert::DEFAULT_GROUP)
    {
        $this->metadataFactory = $metadataFactory;
        $this->defaultGroup = $defaultGroup;
    }

    /**
     * @param MetadataFactoryInterface $metadataFactory
     */
    public static function setMetadataFactory(MetadataFactoryInterface $metadataFactory)
    {
        static::create()->metadataFactory = $metadataFactory;
    }

    /**
     * @param $defaultGroup
     */
    public static function setDefaultGroup($defaultGroup)
    {
        static::create()->defaultGroup = $defaultGroup;
    }

    /**
     * @return Validator
     */
    public static function create()
    {
        if (static::$instance === null) {
            static::$instance = new static(
                new MetadataFactory(new DriverChain(array(new StaticDriver())))
            );
        }

        return static::$instance;
    }

    /**
     * @param Assert $assert
     * @param string $className
     * @param string $group
     *
     * @return $this
     */
    protected function addConstraint(Assert $assert, $className = null, $group = null)
    {
        $className = $this->normalizeClassName($className);
        $group = $this->normalizeGroup($group);

        if (!isset($this->constraints[$className])) {
            $this->constraints[$className] = array();
        }

        if (!isset($this->constraints[$className][$group])) {
            $this->constraints[$className][$group] = Assert::create();
        }

        $this->constraints[$className][$group]->addRules($assert->getRules());

        return $this;
    }

    /**
     * @param string $className
     * @param string $group
     *
     * @return Assert
     */
    protected function getConstraintsByGroup($className = null, $group = null)
    {
        $className = $this->normalizeClassName($className);
        $group = $this->normalizeGroup($group);

        if (!isset($this->constraints[$className])) {
            return Assert::create()->alwaysValid();
        }

        if (!isset($this->constraints[$className][$group])) {
            return Assert::create()->alwaysValid();
        }

        return $this->constraints[$className][$group];
    }

    /**
     * {@inheritdoc}
     */
    public static function assert($value, $constraints = null, $group = null)
    {
        return static::create()->assertConstraints($value, $constraints, $group);
    }

    /**
     * {@inheritdoc}
     */
    protected function assertConstraints($value, $constraints = null, $group = null)
    {
        $group = $this->normalizeGroup($group);

        // If explicit constraints are passed, validate the value against
        // those constraints
        if (null !== $constraints) {
            if (!is_array($constraints)) {
                $constraints = array($constraints);
            }

            foreach ($constraints as $constraint) {
                $this->addConstraint($constraint, null, $group);
            }

            $constraints = $this->getConstraintsByGroup(null, $group);

            try {
                $returnValue = $constraints->assert($value);
            } catch (NestedValidationException $e) {
                throw new ValidationException(
                    $e->getMainMessage(),
                    $e->getMessages(),
                    $e->getCode(),
                    $e->getPrevious()
                );
            }

            return $returnValue;
        }

        // If an object is passed without explicit constraints, validate that
        // object against the constraints defined for the object's class
        if (is_object($value)) {
            $this->addObjectConstraints($value);

            $constraints = $this->getConstraintsByGroup(get_class($value), $group);

            try {
                $returnValue = $constraints->assert($value);
            } catch (NestedValidationException $e) {
                throw new ValidationException(
                    $e->getMainMessage(),
                    $e->getMessages(),
                    $e->getCode(),
                    $e->getPrevious()
                );
            }

            return $returnValue;
        }

        // If an array is passed without explicit constraints, validate each
        // object in the array
        if (is_array($value)) {
            $this->addArrayConstraints($value);

            $returnValue = true;
            foreach ($value as $item) {
                $constraints = $this->getConstraintsByGroup(is_object($item) ? get_class($item) : null, $group);

                try {
                    $returnValue = $returnValue && $constraints->assert($item);
                } catch (NestedValidationException $e) {
                    throw new ValidationException(
                        $e->getMainMessage(),
                        $e->getMessages(),
                        $e->getCode(),
                        $e->getPrevious()
                    );
                }
            }

            return $returnValue;
        }

        throw new \RuntimeException(sprintf(
            'Cannot validate values of type "%s" automatically. Please '.
            'provide a constraint.',
            gettype($value)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public static function validate($value, $constraints = null, $group = null)
    {
        return static::create()->validateConstraints($value, $constraints, $group);
    }

    /**
     * {@inheritdoc}
     */
    protected function validateConstraints($value, $constraints = null, $group = null)
    {
        $group = $this->normalizeGroup($group);

        // If explicit constraints are passed, validate the value against
        // those constraints
        if (null !== $constraints) {
            if (!is_array($constraints)) {
                $constraints = array($constraints);
            }

            foreach ($constraints as $constraint) {
                $this->addConstraint($constraint, null, $group);
            }

            return $this->getConstraintsByGroup(null, $group)->validate($value);
        }

        // If an object is passed without explicit constraints, validate that
        // object against the constraints defined for the object's class
        if (is_object($value)) {
            $this->addObjectConstraints($value);

            return $this->getConstraintsByGroup(get_class($value), $group)->validate($value);
        }

        // If an array is passed without explicit constraints, validate each
        // object in the array
        if (is_array($value)) {
            $this->addArrayConstraints($value);

            $returnValue = true;
            foreach ($value as $item) {
                $constraints = $this->getConstraintsByGroup(is_object($item) ? get_class($item) : null, $group);
                $returnValue = $returnValue && $constraints->validate($item);
            }

            return $returnValue;
        }

        throw new \RuntimeException(sprintf(
            'Cannot validate values of type "%s" automatically. Please '.
            'provide a constraint.',
            gettype($value)
        ));
    }

    /**
     * @param object $object
     */
    protected function addObjectConstraints($object)
    {
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        if ($metadata !== null) {
            /** @var ClassMetadata $classMetadata */
            $classMetadata = $metadata->getRootClassMetadata();

            foreach ($classMetadata->getPropertiesMetadata() as $propertyMetadata) {
                foreach ($propertyMetadata->getConstraints() as $group => $constraints) {
                    $allOf = Assert::create();
                    foreach ($constraints as $constraint) {
                        $allOf->addRules($constraint->getRules());
                    }

                    $this->addConstraint(
                        Assert::create()->attribute($propertyMetadata->getPropertyName(), $allOf),
                        get_class($object),
                        $group
                    );
                }
            }
        }
    }

    /**
     * @param string $className
     *
     * @return ClassMetadata|null
     */
    public static function getMetadataForClass($className)
    {
        return self::create()->metadataFactory->getMetadataForClass($className);
    }

    /**
     * @param string $namespace
     * @param bool   $prepend
     */
    public static function registerValidator($namespace, $prepend = false)
    {
        Assert::registerValidator($namespace, $prepend);
    }

    /**
     * @param mixed $array
     */
    protected function addArrayConstraints($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->addArrayConstraints($value);

                continue;
            }

            // Scalar and null values in the collection are ignored
            if (is_object($value)) {
                $this->addObjectConstraints($value);
            } else {
                throw new \RuntimeException(sprintf(
                    'Cannot validate values of type "%s" automatically. Please '.
                    'provide a constraint.',
                    gettype($value)
                ));
            }
        }
    }

    /**
     * Normalizes the given group.
     *
     * @param string $group
     *
     * @return string
     */
    protected function normalizeGroup($group = null)
    {
        return $group === null || empty($group)  ? $this->defaultGroup : $group;
    }

    /**
     * Normalizes the given className.
     *
     * @param string $className
     *
     * @return string
     */
    protected function normalizeClassName($className = null)
    {
        return $className !== null ? $className : self::class;
    }
}
