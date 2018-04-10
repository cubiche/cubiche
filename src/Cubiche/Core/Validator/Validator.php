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

use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Core\Metadata\Driver\ChainDriver;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Core\Validator\Exception\InvalidArgumentsException;
use Cubiche\Core\Validator\Exception\ValidationException;
use Cubiche\Core\Validator\Mapping\Driver\StaticPHPDriver;
use Cubiche\Core\Validator\Mapping\MethodMetadata;
use Cubiche\Core\Validator\Mapping\PropertyMetadata;
use Cubiche\Core\Validator\Rules\Rule;
use Cubiche\Core\Validator\Visitor\Asserter;

/**
 * Validator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Validator implements ValidatorInterface
{
    /**
     * @var string
     */
    const DEFAULT_GROUP = 'Default';

    /**
     * @var array
     */
    protected $constraints = array();

    /**
     * @var string
     */
    protected $defaultGroup;

    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var Validator
     */
    private static $instance = null;

    /**
     * Validator constructor.
     *
     * @param ClassMetadataFactoryInterface $metadataFactory
     * @param string                        $defaultGroup
     */
    private function __construct(ClassMetadataFactoryInterface $metadataFactory, $defaultGroup = self::DEFAULT_GROUP)
    {
        $this->metadataFactory = $metadataFactory;
        $this->defaultGroup = $defaultGroup;
    }

    /**
     * @param ClassMetadataFactoryInterface $metadataFactory
     */
    public static function setMetadataFactory(ClassMetadataFactoryInterface $metadataFactory)
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
                new ClassMetadataFactory(new ChainDriver(array(new StaticPHPDriver())))
            );
        }

        return static::$instance;
    }

    /**
     * {@inheritdoc}
     */
    public static function validate($value, $constraints = null, $group = null)
    {
        try {
            return static::create()->assertConstraints($value, $constraints, $group);
        } catch (ValidationException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function assert($value, $constraints = null, $group = null)
    {
        return static::create()->assertConstraints($value, $constraints, $group);
    }

    /**
     * @param Rule   $assert
     * @param string $className
     * @param string $group
     *
     * @return $this
     */
    protected function addConstraint(Rule $assert, $className = null, $group = null)
    {
        $className = $this->normalizeClassName($className);
        $group = $this->normalizeGroup($group);

        if (!isset($this->constraints[$className])) {
            $this->constraints[$className] = array();
        }

        if (!isset($this->constraints[$className][$group])) {
            $this->constraints[$className][$group] = new Assertion();
        }

        $this->constraints[$className][$group]->addRule($assert);

        return $this;
    }

    /**
     * @param string $className
     * @param string $group
     *
     * @return Assertion
     */
    protected function getConstraintsByGroup($className = null, $group = null)
    {
        $className = $this->normalizeClassName($className);
        $group = $this->normalizeGroup($group);

        if (!isset($this->constraints[$className])) {
            return Assertion::alwaysValid();
        }

        if (!isset($this->constraints[$className][$group])) {
            return Assertion::alwaysValid();
        }

        return $this->constraints[$className][$group];
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
                return $constraints->assert($value);
            } catch (InvalidArgumentsException $e) {
                throw ValidationException::fromErrors($e->getErrorExceptions());
            } catch (InvalidArgumentException $e) {
                throw ValidationException::fromErrors(array($e));
            }
        }

        // If an object is passed without explicit constraints, validate that
        // object against the constraints defined for the object's class
        if (is_object($value)) {
            $this->addObjectConstraints($value);

            $constraints = $this->getConstraintsByGroup(get_class($value), $group);

            try {
                return $constraints->assert($value);
            } catch (InvalidArgumentsException $e) {
                throw ValidationException::fromErrors($e->getErrorExceptions());
            } catch (InvalidArgumentException $e) {
                throw ValidationException::fromErrors(array($e));
            }
        }

        // If an array is passed without explicit constraints, validate each
        // object in the array
        if (is_array($value)) {
            $this->addArrayConstraints($value);

            $returnValue = true;
            $errors = array();
            foreach ($value as $item) {
                $constraints = $this->getConstraintsByGroup(is_object($item) ? get_class($item) : null, $group);

                try {
                    $returnValue = $returnValue && $constraints->assert($item);
                } catch (InvalidArgumentsException $e) {
                    $errors = Asserter::getErrorExceptions($e, $errors);
                } catch (InvalidArgumentException $e) {
                    $errors[] = $e;
                }
            }

            if (!empty($errors)) {
                throw ValidationException::fromErrors($errors);
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
        $classMetadata = $this->metadataFactory->getMetadataFor(get_class($object));
        if ($classMetadata !== null) {
            /** @var PropertyMetadata $propertyMetadata */
            foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
                foreach ($propertyMetadata->constraints() as $group => $constraints) {
                    $allOf = new Assertion();
                    foreach ($constraints as $constraint) {
                        $allOf->addRule($constraint);
                    }

                    $this->addConstraint(
                        Assertion::property($propertyMetadata->propertyName(), $allOf),
                        get_class($object),
                        $group
                    );
                }
            }

            /** @var MethodMetadata $methodMetadata */
            foreach ($classMetadata->methodsMetadata() as $methodMetadata) {
                foreach ($methodMetadata->constraints() as $group => $constraints) {
                    $allOf = new Assertion();
                    foreach ($constraints as $constraint) {
                        $allOf->addRule($constraint);
                    }

                    $this->addConstraint(
                        Assertion::method($methodMetadata->methodName(), $allOf),
                        get_class($object),
                        $group
                    );
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getMetadataForClass($className)
    {
        return self::create()->metadataFactory->getMetadataFor($className);
    }

    /**
     * {@inheritdoc}
     */
    public static function registerValidator($ruleName, callable $validator)
    {
        Assertion::registerAssert($ruleName, $validator);
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
        return $group === null || empty($group) ? $this->defaultGroup : $group;
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
