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

use Assert\Assertion as BaseAssert;
use Closure;
use Countable;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Exception;
use Throwable;

/**
 * Assert class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @method static static allAlpha(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains letters only for all values
 * @method static static allAlwaysInvalid() Assert always invalid for all values
 * @method static static allAlwaysValid() Assert always valid for all values
 * @method static static allCallback(mixed $value, callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback for all values
 * @method static static allCountBetween(mixed $value, int $min, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array has a count in the given range for all values
 * @method static static allFileExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an existing path for all values
 * @method static static allHostName(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is valid host name for all values
 * @method static static allIsCountable(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or a \Countable for all values
 * @method static static allIsEmpty(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is empty for all values
 * @method static static allIsInstanceOfAny(mixed $value, array $classes, string|callable $message = null, string $propertyPath = null) Assert that value is an instanceof a at least one class on the array of classes for all values
 * @method static static allIsResource(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a resource for all values
 * @method static static allLatitude(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid latitude for all values
 * @method static static allLengthBetween(mixed $value, int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths for all values
 * @method static static allLongitude(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid longitude for all values
 * @method static static allLower(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains lowercase characters only for all values
 * @method static static allMaxCount(mixed $value, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array contains at most a certain number of elements for all values
 * @method static static allMethodExists(mixed $value, string $method, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object for all values
 * @method static static allMethodNotExists(mixed $value, string $method, string | callable $message = null, string $propertyPath = null) Assert that a method does not exist in a class/object for all values
 * @method static static allMinCount(mixed $value, int $min, string|callable $message = null, string $propertyPath = null) Assert that an array contains at least a certain number of elements for all values
 * @method static static allNatural(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains a non-negative integer for all values
 * @method static static allNotContains(mixed $value, string $needle, string | callable $message = null, string $propertyPath = null) Assert that value does not contains a substring for all values
 * @method static static allNoWhitespace(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value does not contains whitespace for all values
 * @method static static allPath(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid path for all values
 * @method static static allPort(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid port for all values
 * @method static static allPropertyNotExists(mixed $value, string $property, string|callable $message = null, string $propertyPath = null) Assert that a property does not exist in a class/object for all values
 * @method static static allThrows(mixed $value, string $class, string|callable $message = null, string $propertyPath = null) Assert that a function throws a certain exception. Subclasses of the exception class will be accepted. for all values
 * @method static static allUpper(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains uppercase characters only for all values
 * @method static static nullOrAlpha(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains letters only or that the value is null
 * @method static static nullOrAlwaysInvalid() Assert always invalid or that the value is null
 * @method static static nullOrAlwaysValid() Assert always valid or that the value is null
 * @method static static nullOrCallback(mixed $value, callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback or that the value is null
 * @method static static nullOrCountBetween(mixed $value, int $min, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array has a count in the given range or that the value is null
 * @method static static nullOrFileExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an existing path or that the value is null
 * @method static static nullOrHostName(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is valid host name or that the value is null
 * @method static static nullOrIsCountable(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or a \Countable or that the value is null
 * @method static static nullOrIsEmpty(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is empty or that the value is null
 * @method static static nullOrIsInstanceOfAny(mixed $value, array $classes, string|callable $message = null, string $propertyPath = null) Assert that value is an instanceof a at least one class on the array of classes or that the value is null
 * @method static static nullOrIsResource(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a resource or that the value is null
 * @method static static nullOrLatitude(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid latitude or that the value is null
 * @method static static nullOrLengthBetween(mixed $value, int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths or that the value is null
 * @method static static nullOrLongitude(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid longitude or that the value is null
 * @method static static nullOrLower(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains lowercase characters only or that the value is null
 * @method static static nullOrMaxCount(mixed $value, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array contains at most a certain number of elements or that the value is null
 * @method static static nullOrMethodExists(mixed $value, string $method, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object or that the value is null
 * @method static static nullOrMethodNotExists(mixed $value, string $method, string | callable $message = null, string $propertyPath = null) Assert that a method does not exist in a class/object or that the value is null
 * @method static static nullOrMinCount(mixed $value, int $min, string|callable $message = null, string $propertyPath = null) Assert that an array contains at least a certain number of elements or that the value is null
 * @method static static nullOrNatural(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains a non-negative integer or that the value is null
 * @method static static nullOrNotContains(mixed $value, string $needle, string | callable $message = null, string $propertyPath = null) Assert that value does not contains a substring or that the value is null
 * @method static static nullOrNoWhitespace(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value does not contains whitespace or that the value is null
 * @method static static nullOrPath(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid path or that the value is null
 * @method static static nullOrPort(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a valid port or that the value is null
 * @method static static nullOrPropertyNotExists(mixed $value, string $property, string|callable $message = null, string $propertyPath = null) Assert that a property does not exist in a class/object or that the value is null
 * @method static static nullOrThrows(mixed $value, string $class, string|callable $message = null, string $propertyPath = null) Assert that a function throws a certain exception. Subclasses of the exception class will be accepted. or that the value is null
 * @method static static nullOrUpper(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value contains uppercase characters only or that the value is null
 */
class Assert extends BaseAssert
{
    const INVALID_ALWAYS_INVALID = 230;
    const INVALID_ALPHA = 231;
    const INVALID_NATURAL = 232;
    const INVALID_IS_COUNTABLE = 233;
    const INVALID_IS_INSTANCE_OF_ANY = 234;
    const INVALID_STRING_NOT_CONTAINS = 235;
    const INVALID_STRING_NO_WHITESPACE = 236;
    const INVALID_STRING_LOWER = 237;
    const INVALID_STRING_UPPER = 238;
    const INVALID_FILE_EXISTS = 239;
    const INVALID_PROPERTY_NOT_EXISTS = 240;
    const INVALID_METHOD_NOT_EXISTS = 241;
    const INVALID_MIN_COUNT = 242;
    const INVALID_MAX_COUNT = 243;
    const INVALID_COUNT_BETWEEN = 244;
    const INVALID_THROWS = 245;
    const INVALID_NOT_ASSERT = 246;
    const INVALID_NONE_OF = 247;
    const INVALID_LATITUDE = 248;
    const INVALID_LONGITUDE = 249;
    const INVALID_HOST_NAME = 250;
    const INVALID_PATH = 251;
    const INVALID_CALLBACK = 252;
    const INVALID_UNIQUE_VALUE = 260;
    const INVALID_CUSTOM_ASSERT = 280;

    /**
     * @var string
     */
    protected static $exceptionClass = 'Cubiche\Core\Validator\Exception\InvalidArgumentException';

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     */
    public static function alwaysValid($value, $message = null, $propertyPath = null)
    {
        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function alwaysInvalid($value, $message = null, $propertyPath = null)
    {
        $message = sprintf(
            static::generateMessage($message ?: 'Value "%s" is always invalid.'),
            static::stringify($value)
        );

        throw static::createException($value, $message, static::INVALID_ALWAYS_INVALID, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function alpha($value, $message = null, $propertyPath = null)
    {
        if (!ctype_alpha($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be aplha, type %s given.'),
                static::stringify($value),
                gettype($value)
            );

            throw static::createException($value, $message, static::INVALID_ALPHA, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param int                  $minLength
     * @param int                  $maxLength
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     * @param string               $encoding
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function lengthBetween($value, $minLength, $maxLength, $message = null, $propertyPath = null, $encoding = 'utf8')
    {
        return static::betweenLength($value, $minLength, $maxLength, $message, $propertyPath, $encoding);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function lower($value, $message = null, $propertyPath = null)
    {
        if (!ctype_lower($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to contain lowercase characters only.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_STRING_LOWER, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function upper($value, $message = null, $propertyPath = null)
    {
        if (!ctype_upper($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to contain uppercase characters only.'),
                static::stringify($value),
                gettype($value)
            );

            throw static::createException($value, $message, static::INVALID_STRING_UPPER, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function natural($value, $message = null, $propertyPath = null)
    {
        if (!is_int($value) || $value < 0) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a non-negative integer, type %s given.'),
                static::stringify($value),
                gettype($value)
            );

            throw static::createException($value, $message, static::INVALID_NATURAL, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isCountable($value, $message = null, $propertyPath = null)
    {
        if (!is_array($value) && !($value instanceof Countable)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a countable, type %s given.'),
                static::stringify($value),
                gettype($value)
            );

            throw static::createException($value, $message, static::INVALID_IS_COUNTABLE, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param array                $classes
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isInstanceOfAny($value, array $classes, $message = null, $propertyPath = null)
    {
        foreach ($classes as $class) {
            if ($value instanceof $class) {
                return true;
            }
        }

        $message = sprintf(
            static::generateMessage($message ?: 'Value "%s" expected to be an instance of any of %s, type %s given.'),
            static::stringify($value),
            implode(', ', array_map(array('static', 'stringify'), $classes)),
            gettype($value)
        );

        throw static::createException($value, $message, static::INVALID_IS_INSTANCE_OF_ANY, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isEmpty($value, $message = null, $propertyPath = null)
    {
        return parent::noContent($value, $message, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|null          $type
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function isResource($value, $type = null, $message = null, $propertyPath = null)
    {
        parent::isResource($value, $message, $propertyPath);

        if ($type && $type !== get_resource_type($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a resource of type %s.'),
                static::stringify($value),
                static::stringify($type)
            );

            throw static::createException($value, $message, static::INVALID_RESOURCE, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $string
     * @param string               $needle
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     * @param string               $encoding
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function notContains($string, $needle, $message = null, $propertyPath = null, $encoding = 'utf8')
    {
        static::string($string, $message, $propertyPath);

        if (false !== \mb_strpos($string, $needle, null, $encoding)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" was not expected to be contained in "%s".'),
                static::stringify($string),
                static::stringify($needle)
            );

            $constraints = array('needle' => $needle, 'encoding' => $encoding);
            throw static::createException($string, $message, static::INVALID_STRING_NOT_CONTAINS, $propertyPath, $constraints);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function noWhitespace($value, $message = null, $propertyPath = null)
    {
        if (preg_match('#\s#', $value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" was not expected to have whitespace.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_STRING_NO_WHITESPACE, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function fileExists($value, $message = null, $propertyPath = null)
    {
        static::string($value);

        if (!file_exists($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'The file %s does not exist.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_FILE_EXISTS, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string               $property
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function propertyNotExists($value, $property, $message = null, $propertyPath = null)
    {
        static::objectOrClass($value);

        if (property_exists($value, $property)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Class "%s" does not expected to have property "%s".'),
                static::stringify($value),
                static::stringify($property)
            );

            throw static::createException($value, $message, static::INVALID_PROPERTY_NOT_EXISTS, $propertyPath);
        }

        return true;
    }

    /**
     * @param string               $value
     * @param mixed                $method
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     */
    public static function methodExists($value, $method, $message = null, $propertyPath = null)
    {
        return parent::methodExists($method, $value, $message, $propertyPath);
    }

    /**
     * @param string               $value
     * @param mixed                $method
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function methodNotExists($value, $method, $message = null, $propertyPath = null)
    {
        if (is_object($value) && method_exists($value, $method)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Class "%s" does not expected to have method "%s".'),
                static::stringify($value),
                static::stringify($method)
            );

            throw static::createException($method, $message, static::INVALID_METHOD_NOT_EXISTS, $propertyPath);
        }

        return true;
    }

    /**
     * @param string               $value
     * @param mixed                $min
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function minCount($value, $min, $message = null, $propertyPath = null)
    {
        if (count($value) < $min) {
            $message = sprintf(
                static::generateMessage($message ?: 'Expected an array to contain at least %s elements, %s given.'),
                static::stringify($min),
                static::stringify(count($value))
            );

            throw static::createException($value, $message, static::INVALID_MIN_COUNT, $propertyPath);
        }

        return true;
    }

    /**
     * @param string               $value
     * @param mixed                $max
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function maxCount($value, $max, $message = null, $propertyPath = null)
    {
        if (count($value) > $max) {
            $message = sprintf(
                static::generateMessage($message ?: 'Expected an array to contain at most %s elements, %s given.'),
                static::stringify($max),
                static::stringify(count($value))
            );

            throw static::createException($value, $message, static::INVALID_MAX_COUNT, $propertyPath);
        }

        return true;
    }

    /**
     * @param string               $value
     * @param mixed                $min
     * @param mixed                $max
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function countBetween($value, $min, $max, $message = null, $propertyPath = null)
    {
        $count = count($value);
        if ($count < $min || $count > $max) {
            $message = sprintf(
                static::generateMessage($message ?: 'Expected an array to contain between %s and %s elements, %s given.'),
                static::stringify($min),
                static::stringify($max),
                static::stringify(count($value))
            );

            throw static::createException($value, $message, static::INVALID_COUNT_BETWEEN, $propertyPath);
        }

        return true;
    }

    /**
     * @param Closure              $expression
     * @param string               $class
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function throws(Closure $expression, $class = 'Exception', $message = null, $propertyPath = null)
    {
        static::string($class);
        $actual = 'none';

        try {
            $expression();
        } catch (Exception $e) {
            $actual = get_class($e);
            if ($e instanceof $class) {
                return true;
            }
        } catch (Throwable $e) {
            $actual = get_class($e);
            if ($e instanceof $class) {
                return true;
            }
        }

        $message = sprintf(
            static::generateMessage($message ?: 'Expected to throw "%s", got "%s".'),
            static::stringify($class),
            static::stringify($actual)
        );

        throw static::createException($expression, $message, static::INVALID_THROWS, $propertyPath);
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function latitude($value, $message = null, $propertyPath = null)
    {
        try {
            static::regex($value, '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $message, $propertyPath);
        } catch (AssertionFailedException $e) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a valid latitude.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_LATITUDE, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function longitude($value, $message = null, $propertyPath = null)
    {
        try {
            static::regex($value, '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $message, $propertyPath);
        } catch (AssertionFailedException $e) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a valid longitude.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_LONGITUDE, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function hostName($value, $message = null, $propertyPath = null)
    {
        try {
            //valid chars check
            static::regex($value, "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $message, $propertyPath);

            //overall length check
            static::regex($value, '/^.{1,253}$/', $message, $propertyPath);

            //length of each label
            static::regex($value, "/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $message, $propertyPath);
        } catch (AssertionFailedException $e) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a valid "host name".'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_HOST_NAME, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function path($value, $message = null, $propertyPath = null)
    {
        $filteredValue = parse_url($value, PHP_URL_PATH);
        if ($filteredValue === null || strlen($filteredValue) != strlen($value)) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be a valid path.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_PATH, $propertyPath);
        }

        return true;
    }

    /**
     * @param mixed                $value
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function port($value, $message = null, $propertyPath = null)
    {
        $message = \sprintf(
            static::generateMessage($message ?: 'Value "%s" expected to be a valid port.'),
            static::stringify($value)
        );

        static::integer($value, $message, $propertyPath);
        static::range($value, 0, 65535, $message, $propertyPath);

        return true;
    }

    /**
     * @param mixed                $value
     * @param callable             $callback
     * @param array                $arguments
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public static function callback($value, $callback, array $arguments, $message = null, $propertyPath = null)
    {
        static::isCallable($callback);

        array_unshift($arguments, $value);
        if (false === \call_user_func_array($callback, $arguments)) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Provided "%s" is invalid according to custom callback rule.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_CALLBACK, $propertyPath);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function generateMessage($message = null)
    {
        return parent::generateMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public static function stringify($value)
    {
        return parent::stringify($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function createException($value, $message, $code, $propertyPath = null, array $constraints = array())
    {
        return parent::createException($value, $message, $code, $propertyPath, $constraints);
    }
}
