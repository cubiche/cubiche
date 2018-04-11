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
 */
class Assert extends BaseAssert
{
    const INVALID_ALWAYS_INVALID = 230;
    const INVALID_ALPHA = 231;
    const INVALID_NATURAL = 232;
    const INVALID_IS_COUNTABLE = 233;
    const INVALID_IS_INSTANCE_OF_ANY = 234;
    const INVALID_IS_EMPTY = 235;
    const INVALID_STRING_NOT_CONTAINS = 236;
    const INVALID_STRING_NO_WHITESPACE = 237;
    const INVALID_STRING_LOWER = 238;
    const INVALID_STRING_UPPER = 239;
    const INVALID_FILE_EXISTS = 240;
    const INVALID_PROPERTY_NOT_EXISTS = 241;
    const INVALID_METHOD_NOT_EXISTS = 242;
    const INVALID_MIN_COUNT = 243;
    const INVALID_MAX_COUNT = 244;
    const INVALID_COUNT_BETWEEN = 245;
    const INVALID_THROWS = 246;
    const INVALID_NOT_ASSERT = 247;
    const INVALID_NONE_OF = 248;

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

//    /**
//     * @param mixed                $value
//     * @param string|callable|null $message
//     * @param string|null          $propertyPath
//     *
//     * @return bool
//     *
//     * @throws InvalidArgumentException
//     */
//    public static function stringType($value, $message = null, $propertyPath = null)
//    {
//        return static::string($value, $message, $propertyPath);
//    }

//    /**
//     * @param mixed                $value
//     * @param string|callable|null $message
//     * @param string|null          $propertyPath
//     *
//     * @return bool
//     *
//     * @throws InvalidArgumentException
//     */
//    public static function integerType($value, $message = null, $propertyPath = null)
//    {
//        return static::integer($value, $message, $propertyPath);
//    }

//    /**
//     * @param mixed                $value
//     * @param string|callable|null $message
//     * @param string|null          $propertyPath
//     *
//     * @return bool
//     *
//     * @throws InvalidArgumentException
//     */
//    public static function booleanType($value, $message = null, $propertyPath = null)
//    {
//        return static::boolean($value, $message, $propertyPath);
//    }

//    /**
//     * @param mixed                $value
//     * @param string|callable|null $message
//     * @param string|null          $propertyPath
//     *
//     * @return bool
//     *
//     * @throws InvalidArgumentException
//     */
//    public static function floatType($value, $message = null, $propertyPath = null)
//    {
//        return static::float($value, $message, $propertyPath);
//    }

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
        if (!empty($value)) {
            $message = sprintf(
                static::generateMessage($message ?: 'Value "%s" expected to be empty.'),
                static::stringify($value)
            );

            throw static::createException($value, $message, static::INVALID_IS_EMPTY, $propertyPath);
        }

        return true;
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
        if (preg_match('/^\s*$/', $value)) {
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
     * {@inheritdoc}
     */
    public static function stringify($value)
    {
        return parent::stringify($value);
    }

//    /**
//     * {@inheritdoc}
//     */
//    public static function __callStatic($method, $args)
//    {
////        if ('nullOr' === substr($method, 0, 6)) {
//////            die(var_export($method));
////            if (null !== $args[0]) {
////                $method = lcfirst(substr($method, 6));
////
////                return call_user_func_array(array('static', $method), $args);
////            }
////            return true;
////        }

////        die(var_export($method));
////        if (0 === strpos($method, 'not')) {
////            $method = lcfirst(substr($method, 3));
////            die(var_export($method));
////            if (method_exists(get_called_class(), $method)) {
////                return call_user_func_array(array(get_called_class(), $method), $args);
////            }
////        }

//        return parent::__callStatic($method, $args);
//    }
}
