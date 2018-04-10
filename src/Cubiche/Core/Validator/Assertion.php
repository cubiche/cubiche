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

use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Core\Validator\Rules\Generic\All;
use Cubiche\Core\Validator\Rules\Generic\Callback;
use Cubiche\Core\Validator\Rules\Generic\NullOr;
use Cubiche\Core\Validator\Rules\Group\AllOf;
use Cubiche\Core\Validator\Rules\Rule;
use Cubiche\Core\Validator\Visitor\Asserter;
use LogicException;
use ReflectionClass;

/**
 * Assertion class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @method static Rule stringType(string|callable $message = null, string $propertyPath = null) Assert that value is a string
 * @method static Rule integerType(string|callable $message = null, string $propertyPath = null) Assert that value is a string
 * @method static Rule notBlank(string|callable $message = null, string $propertyPath = null) Assert that value is not blank
 * @method static Rule property(string $name, Rule $assert, string|callable $message = null, string $propertyPath = null) Assert that value is not blank
 * @method static Rule method(string $name, Rule $assert, string|callable $message = null, string $propertyPath = null) Assert that value is not blank
 * @method static Rule allOf(Rule ...$assertions) Assert all the assertions
 * @method static Rule oneOf(Rule ...$assertions) Assert one of the assertions
 * @method static Rule noneOf(Rule ...$assertions) Assert none of the assertions
 * @method static Rule alwaysValid() Assert always valid
 * @method static Rule alwaysInvalid() Assert always valid
 */
class Assertion extends AllOf
{
    /**
     * @var array
     */
    protected static $ruleNamespaces = array(
        'Cubiche\\Core\\Validator\\Rules\\',
        'Cubiche\\Core\\Validator\\Rules\\Common\\',
        'Cubiche\\Core\\Validator\\Rules\\Comparison\\',
        'Cubiche\\Core\\Validator\\Rules\\Generic\\',
        'Cubiche\\Core\\Validator\\Rules\\Group\\',
        'Cubiche\\Core\\Validator\\Rules\\Numeric\\',
        'Cubiche\\Core\\Validator\\Rules\\Object\\',
        'Cubiche\\Core\\Validator\\Rules\\String\\',
    );

    /**
     * @var array
     */
    protected static $asserts = array();

    /**
     * @param string $ruleName
     * @param array  $arguments
     *
     * @return self
     */
    public static function __callStatic($ruleName, $arguments)
    {
        $validator = new static();

        return $validator->__call($ruleName, $arguments);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return self
     */
    public function __call($method, $arguments)
    {
        return $this->addRule(static::buildRule($method, $arguments));
    }

    /**
     * @param mixed $ruleName
     * @param array $arguments
     *
     * @return Rules\Rule
     *
     * @throws \Exception
     */
    public static function buildRule($ruleName, $arguments = [])
    {
        foreach (static::$ruleNamespaces as $namespace) {
            $className = $namespace.ucfirst($ruleName);
            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            return $reflection->newInstanceArgs($arguments);
        }

        if (static::hasAssert($ruleName)) {
            return static::getAssert($ruleName);
        }

        if (0 === strpos($ruleName, 'nullOr')) {
            $method = substr($ruleName, 6);

            return new NullOr(static::buildRule($method, $arguments));
        }

        if (0 === strpos($ruleName, 'all')) {
            $method = substr($ruleName, 3);

            return new All(static::buildRule($method, $arguments));
        }

        throw new LogicException(sprintf('"%s" is not a valid rule name', $ruleName));
    }

    /**
     * @param string   $ruleName
     * @param callable $assert
     */
    public static function registerAssert($ruleName, callable $assert)
    {
        static::$asserts[$ruleName] = new Callback($assert);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function hasAssert($name)
    {
        return isset(static::$asserts[$name]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function getAssert($name)
    {
        if (!static::hasAssert($name)) {
            throw new LogicException('No assert Assertion#'.$name.' exists.');
        }

        return static::$asserts[$name];
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value)
    {
        try {
            $this->accept(new Asserter(), $value);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function assert($value, $message = null, $propertyPath = null)
    {
        return $this->accept(new Asserter(), $value, $message, $propertyPath);
    }
}
