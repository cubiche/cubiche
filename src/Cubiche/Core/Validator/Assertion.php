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
use Cubiche\Core\Validator\Rules\Generic\Not;
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
 * @method static static allOf(Assertion ...$assertions) Assert all the assertions
 * @method static static alnum(string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric
 * @method static static alpha(string|callable $message = null, string $propertyPath = null) Assert that value contains letters only
 * @method static static alwaysInvalid() Assert always invalid
 * @method static static alwaysValid() Assert always valid
 * @method static static base64(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined
 * @method static static between(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit
 * @method static static betweenExclusive(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit
 * @method static static boolean(string|callable $message = null, string $propertyPath = null) Assert that value is php boolean
 * @method static static choice(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices
 * @method static static choicesNotEmpty(array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content
 * @method static static classExists(string|callable $message = null, string $propertyPath = null) Assert that the class exists
 * @method static static contains(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars
 * @method static static count(int $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count
 * @method static static countBetween(int $min, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array has a count in the given range
 * @method static static date(string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format
 * @method static static defined(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined
 * @method static static digit(string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit
 * @method static static directory(string|callable $message = null, string $propertyPath = null) Assert that a directory exists
 * @method static static e164(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number
 * @method static static each(Assertion $keyAssert = null, Assertion $valueAssert = null, string|callable $message = null, string $propertyPath = null) Assert the key or value of each entry
 * @method static static email(string|callable $message = null, string $propertyPath = null) Assert that value is an email address (using input_filter/FILTER_VALIDATE_EMAIL)
 * @method static static endsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars
 * @method static static eq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using == )
 * @method static static extensionLoaded(string|callable $message = null, string $propertyPath = null) Assert that extension is loaded
 * @method static static extensionVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed
 * @method static static false(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False
 * @method static static file(string|callable $message = null, string $propertyPath = null) Assert that a file exists
 * @method static static fileExists(string|callable $message = null, string $propertyPath = null) Assert that value is an existing path
 * @method static static float(string|callable $message = null, string $propertyPath = null) Assert that value is a php float
 * @method static static greaterOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit
 * @method static static greaterThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit
 * @method static static implementsInterface(string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface
 * @method static static inArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice()
 * @method static static integer(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer
 * @method static static integerish(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish
 * @method static static interfaceExists(string|callable $message = null, string $propertyPath = null) Assert that the interface exists
 * @method static static ip(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address
 * @method static static ipv4(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address
 * @method static static ipv6(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address
 * @method static static isArray(string|callable $message = null, string $propertyPath = null) Assert that value is an array
 * @method static static isArrayAccessible(string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object
 * @method static static isCallable(string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable
 * @method static static isCountable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a \Countable
 * @method static static isEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is empty
 * @method static static isInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name
 * @method static static isInstanceOfAny(array $classes, string|callable $message = null, string $propertyPath = null) Assert that value is an instanceof a at least one class on the array of classes
 * @method static static isJsonString(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string
 * @method static static isObject(string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object
 * @method static static isResource(string|callable $message = null, string $propertyPath = null) Assert that value is a resource
 * @method static static isTraversable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object
 * @method static static keyExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array
 * @method static static keyIsset(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset()
 * @method static static keyNotExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array
 * @method static static length(int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length
 * @method static static lengthBetween(int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths
 * @method static static lessOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or equal than given limit
 * @method static static lessThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit
 * @method static static lower(string|callable $message = null, string $propertyPath = null) Assert that value contains lowercase characters only
 * @method static static max(mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit
 * @method static static maxCount(int $max, string|callable $message = null, string $propertyPath = null) Assert that an array contains at most a certain number of elements
 * @method static static maxLength(int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars
 * @method static static method(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a method value
 * @method static static methodExists(mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object
 * @method static static methodNotExists(string $method, string|callable $message = null, string $propertyPath = null) Assert that a method does not exist in a class/object
 * @method static static min(mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit
 * @method static static minCount(int $min, string|callable $message = null, string $propertyPath = null) Assert that an array contains at least a certain number of elements
 * @method static static minLength(int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long
 * @method static static natural(string|callable $message = null, string $propertyPath = null) Assert that value contains a non-negative integer
 * @method static static noContent(string|callable $message = null, string $propertyPath = null) Assert that value is empty
 * @method static static noneOf(Assertion ...$assertions) Assert none of the assertions
 * @method static static notBlank(string|callable $message = null, string $propertyPath = null) Assert that value is not blank
 * @method static static notContains(string $needle, string|callable $message = null, string $propertyPath = null) Assert that value does not contains a substring
 * @method static static notEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is not empty
 * @method static static notEmptyKey(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty
 * @method static static notEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == )
 * @method static static notInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices
 * @method static static notIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name
 * @method static static notNull(string|callable $message = null, string $propertyPath = null) Assert that value is not null
 * @method static static notSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === )
 * @method static static noWhitespace(string|callable $message = null, string $propertyPath = null) Assert that value does not contains whitespace
 * @method static static null(string|callable $message = null, string $propertyPath = null) Assert that value is null
 * @method static static numeric(string|callable $message = null, string $propertyPath = null) Assert that value is numeric
 * @method static static objectOrClass(string|callable $message = null, string $propertyPath = null) Assert that the value is an object, or a class that exists
 * @method static static oneOf(Assertion ...$assertions) Assert one of the assertions
 * @method static static phpVersion(mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version
 * @method static static propertiesExist(array $properties, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the properties all exist
 * @method static static property(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a property value
 * @method static static propertyExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the property exists
 * @method static static propertyNotExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that a property does not exist in a class/object
 * @method static static range(mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers
 * @method static static readable(string|callable $message = null, string $propertyPath = null) Assert that the value is something readable
 * @method static static regex(string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex
 * @method static static same(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===)
 * @method static static satisfy(callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback
 * @method static static scalar(string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar
 * @method static static startsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars
 * @method static static string(string|callable $message = null, string $propertyPath = null) Assert that value is a string
 * @method static static subclassOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name
 * @method static static throws(string $class, string|callable $message = null, string $propertyPath = null) Assert that a function throws a certain exception. Subclasses of the exception class will be accepted..
 * @method static static true(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True
 * @method static static upper(string|callable $message = null, string $propertyPath = null) Assert that value contains uppercase characters only
 * @method static static url(string|callable $message = null, string $propertyPath = null) Assert that value is an URL
 * @method static static uuid(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID
 * @method static static version(string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions
 * @method static static writeable(string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable
 * @method static static allAlnum(string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric for all values
 * @method static static allAlpha(string|callable $message = null, string $propertyPath = null) Assert that value contains letters only for all values
 * @method static static allAlwaysInvalid() Assert always invalid for all values
 * @method static static allAlwaysValid() Assert always valid for all values
 * @method static static allBase64(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined for all values
 * @method static static allBetween(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit for all values
 * @method static static allBetweenExclusive(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit for all values
 * @method static static allBoolean(string|callable $message = null, string $propertyPath = null) Assert that value is php boolean for all values
 * @method static static allChoice(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices for all values
 * @method static static allChoicesNotEmpty(array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content for all values
 * @method static static allClassExists(string|callable $message = null, string $propertyPath = null) Assert that the class exists for all values
 * @method static static allContains(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars for all values
 * @method static static allCount(int $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count for all values
 * @method static static allCountBetween(int $min, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array has a count in the given range for all values
 * @method static static allDate(string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format for all values
 * @method static static allDefined(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined for all values
 * @method static static allDigit(string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit for all values
 * @method static static allDirectory(string|callable $message = null, string $propertyPath = null) Assert that a directory exists for all values
 * @method static static allE164(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number for all values
 * @method static static allEach(Assertion $keyAssert = null, Assertion $valueAssert = null, string|callable $message = null, string $propertyPath = null) Assert the key or value of each entry for all values
 * @method static static allEmail(string|callable $message = null, string $propertyPath = null) Assert that value is an email address (using input_filter/FILTER_VALIDATE_EMAIL) for all values
 * @method static static allEndsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars for all values
 * @method static static allEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using == ) for all values
 * @method static static allExtensionLoaded(string|callable $message = null, string $propertyPath = null) Assert that extension is loaded for all values
 * @method static static allExtensionVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed for all values
 * @method static static allFalse(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False for all values
 * @method static static allFile(string|callable $message = null, string $propertyPath = null) Assert that a file exists for all values
 * @method static static allFileExists(string|callable $message = null, string $propertyPath = null) Assert that value is an existing path for all values
 * @method static static allFloat(string|callable $message = null, string $propertyPath = null) Assert that value is a php float for all values
 * @method static static allGreaterOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit for all values
 * @method static static allGreaterThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit for all values
 * @method static static allImplementsInterface(string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface for all values
 * @method static static allInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice() for all values
 * @method static static allInteger(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer for all values
 * @method static static allIntegerish(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish for all values
 * @method static static allInterfaceExists(string|callable $message = null, string $propertyPath = null) Assert that the interface exists for all values
 * @method static static allIp(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address for all values
 * @method static static allIpv4(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address for all values
 * @method static static allIpv6(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address for all values
 * @method static static allIsArray(string|callable $message = null, string $propertyPath = null) Assert that value is an array for all values
 * @method static static allIsArrayAccessible(string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object for all values
 * @method static static allIsCallable(string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable for all values
 * @method static static allIsCountable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a \Countable for all values
 * @method static static allIsEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is empty for all values
 * @method static static allIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name for all values
 * @method static static allIsInstanceOfAny(array $classes, string|callable $message = null, string $propertyPath = null) Assert that value is an instanceof a at least one class on the array of classes for all values
 * @method static static allIsJsonString(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string for all values
 * @method static static allIsObject(string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object for all values
 * @method static static allIsResource(string|callable $message = null, string $propertyPath = null) Assert that value is a resource for all values
 * @method static static allIsTraversable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object for all values
 * @method static static allKeyExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array for all values
 * @method static static allKeyIsset(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset() for all values
 * @method static static allKeyNotExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array for all values
 * @method static static allLength(int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length for all values
 * @method static static allLengthBetween(int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths for all values
 * @method static static allLessOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or equal than given limit for all values
 * @method static static allLessThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit for all values
 * @method static static allLower(string|callable $message = null, string $propertyPath = null) Assert that value contains lowercase characters only for all values
 * @method static static allMax(mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit for all values
 * @method static static allMaxCount(int $max, string|callable $message = null, string $propertyPath = null) Assert that an array contains at most a certain number of elements for all values
 * @method static static allMaxLength(int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars for all values
 * @method static static allMethod(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a method value for all values
 * @method static static allMethodExists(mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object for all values
 * @method static static allMethodNotExists(string $method, string|callable $message = null, string $propertyPath = null) Assert that a method does not exist in a class/object for all values
 * @method static static allMin(mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit for all values
 * @method static static allMinCount(int $min, string|callable $message = null, string $propertyPath = null) Assert that an array contains at least a certain number of elements for all values
 * @method static static allMinLength(int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long for all values
 * @method static static allNatural(string|callable $message = null, string $propertyPath = null) Assert that value contains a non-negative integer for all values
 * @method static static allNoContent(string|callable $message = null, string $propertyPath = null) Assert that value is empty for all values
 * @method static static allNotBlank(string|callable $message = null, string $propertyPath = null) Assert that value is not blank for all values
 * @method static static allNotContains(string $needle, string|callable $message = null, string $propertyPath = null) Assert that value does not contains a substring for all values
 * @method static static allNotEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is not empty for all values
 * @method static static allNotEmptyKey(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty for all values
 * @method static static allNotEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == ) for all values
 * @method static static allNotInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices for all values
 * @method static static allNotIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name for all values
 * @method static static allNotNull(string|callable $message = null, string $propertyPath = null) Assert that value is not null for all values
 * @method static static allNotSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === ) for all values
 * @method static static allNoWhitespace(string|callable $message = null, string $propertyPath = null) Assert that value does not contains whitespace for all values
 * @method static static allNull(string|callable $message = null, string $propertyPath = null) Assert that value is null for all values
 * @method static static allNumeric(string|callable $message = null, string $propertyPath = null) Assert that value is numeric for all values
 * @method static static allObjectOrClass(string|callable $message = null, string $propertyPath = null) Assert that the value is an object, or a class that exists for all values
 * @method static static allPhpVersion(mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version for all values
 * @method static static allPropertiesExist(array $properties, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the properties all exist for all values
 * @method static static allProperty(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a property value for all values
 * @method static static allPropertyExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the property exists for all values
 * @method static static allPropertyNotExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that a property does not exist in a class/object for all values
 * @method static static allRange(mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers for all values
 * @method static static allReadable(string|callable $message = null, string $propertyPath = null) Assert that the value is something readable for all values
 * @method static static allRegex(string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex for all values
 * @method static static allSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===) for all values
 * @method static static allSatisfy(callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback for all values
 * @method static static allScalar(string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar for all values
 * @method static static allStartsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars for all values
 * @method static static allString(string|callable $message = null, string $propertyPath = null) Assert that value is a string for all values
 * @method static static allSubclassOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name for all values
 * @method static static allThrows(string $class, string|callable $message = null, string $propertyPath = null) Assert that a function throws a certain exception. Subclasses of the exception class will be accepted. for all values
 * @method static static allTrue(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True for all values
 * @method static static allUpper(string|callable $message = null, string $propertyPath = null) Assert that value contains uppercase characters only for all values
 * @method static static allUrl(string|callable $message = null, string $propertyPath = null) Assert that value is an URL for all values
 * @method static static allUuid(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID for all values
 * @method static static allVersion(string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions for all values
 * @method static static allWriteable(string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable for all values
 * @method static static nullOrAlnum(string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric or that the value is null
 * @method static static nullOrAlpha(string|callable $message = null, string $propertyPath = null) Assert that value contains letters only or that the value is null
 * @method static static nullOrAlwaysInvalid() Assert always invalid or that the value is null
 * @method static static nullOrAlwaysValid() Assert always valid or that the value is null
 * @method static static nullOrBase64(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined or that the value is null
 * @method static static nullOrBetween(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit or that the value is null
 * @method static static nullOrBetweenExclusive(mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit or that the value is null
 * @method static static nullOrBoolean(string|callable $message = null, string $propertyPath = null) Assert that value is php boolean or that the value is null
 * @method static static nullOrChoice(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices or that the value is null
 * @method static static nullOrChoicesNotEmpty(array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content or that the value is null
 * @method static static nullOrClassExists(string|callable $message = null, string $propertyPath = null) Assert that the class exists or that the value is null
 * @method static static nullOrContains(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars or that the value is null
 * @method static static nullOrCount(int $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count or that the value is null
 * @method static static nullOrCountBetween(int $min, int $max, string|callable $message = null, string $propertyPath = null) Assert that an array has a count in the given range or that the value is null
 * @method static static nullOrDate(string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format or that the value is null
 * @method static static nullOrDefined(string|callable $message = null, string $propertyPath = null) Assert that a constant is defined or that the value is null
 * @method static static nullOrDigit(string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit or that the value is null
 * @method static static nullOrDirectory(string|callable $message = null, string $propertyPath = null) Assert that a directory exists or that the value is null
 * @method static static nullOrE164(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number or that the value is null
 * @method static static nullOrEmail(string|callable $message = null, string $propertyPath = null) Assert that value is an email address (using input_filter/FILTER_VALIDATE_EMAIL) or that the value is null
 * @method static static nullOrEndsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars or that the value is null
 * @method static static nullOrEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using == ) or that the value is null
 * @method static static nullOrExtensionLoaded(string|callable $message = null, string $propertyPath = null) Assert that extension is loaded or that the value is null
 * @method static static nullOrExtensionVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed or that the value is null
 * @method static static nullOrFalse(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False or that the value is null
 * @method static static nullOrFile(string|callable $message = null, string $propertyPath = null) Assert that a file exists or that the value is null
 * @method static static nullOrFileExists(string|callable $message = null, string $propertyPath = null) Assert that value is an existing path or that the value is null
 * @method static static nullOrFloat(string|callable $message = null, string $propertyPath = null) Assert that value is a php float or that the value is null
 * @method static static nullOrGreaterOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit or that the value is null
 * @method static static nullOrGreaterThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit or that the value is null
 * @method static static nullOrImplementsInterface(string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface or that the value is null
 * @method static static nullOrInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice() or that the value is null
 * @method static static nullOrInteger(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer or that the value is null
 * @method static static nullOrIntegerish(string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish or that the value is null
 * @method static static nullOrInterfaceExists(string|callable $message = null, string $propertyPath = null) Assert that the interface exists or that the value is null
 * @method static static nullOrIp(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address or that the value is null
 * @method static static nullOrIpv4(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address or that the value is null
 * @method static static nullOrIpv6(int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address or that the value is null
 * @method static static nullOrIsArray(string|callable $message = null, string $propertyPath = null) Assert that value is an array or that the value is null
 * @method static static nullOrIsArrayAccessible(string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object or that the value is null
 * @method static static nullOrIsCallable(string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable or that the value is null
 * @method static static nullOrIsCountable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a \Countable or that the value is null
 * @method static static nullOrIsEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is empty or that the value is null
 * @method static static nullOrIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name or that the value is null
 * @method static static nullOrIsInstanceOfAny(array $classes, string|callable $message = null, string $propertyPath = null) Assert that value is an instanceof a at least one class on the array of classes or that the value is null
 * @method static static nullOrIsJsonString(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string or that the value is null
 * @method static static nullOrIsObject(string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object or that the value is null
 * @method static static nullOrIsResource(string|callable $message = null, string $propertyPath = null) Assert that value is a resource or that the value is null
 * @method static static nullOrIsTraversable(string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object or that the value is null
 * @method static static nullOrKeyExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array or that the value is null
 * @method static static nullOrKeyIsset(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset() or that the value is null
 * @method static static nullOrKeyNotExists(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array or that the value is null
 * @method static static nullOrLength(int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length or that the value is null
 * @method static static nullOrLengthBetween(int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min,max lengths or that the value is null
 * @method static static nullOrLessOrEqualThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or equal than given limit or that the value is null
 * @method static static nullOrLessThan(mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit or that the value is null
 * @method static static nullOrLower(string|callable $message = null, string $propertyPath = null) Assert that value contains lowercase characters only or that the value is null
 * @method static static nullOrMax(mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit or that the value is null
 * @method static static nullOrMaxCount(int $max, string|callable $message = null, string $propertyPath = null) Assert that an array contains at most a certain number of elements or that the value is null
 * @method static static nullOrMaxLength(int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars or that the value is null
 * @method static static nullOrMethod(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a method value or that the value is null
 * @method static static nullOrMethodExists(mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object or that the value is null
 * @method static static nullOrMethodNotExists(string $method, string|callable $message = null, string $propertyPath = null) Assert that a method does not exist in a class/object or that the value is null
 * @method static static nullOrMin(mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit or that the value is null
 * @method static static nullOrMinCount(int $min, string|callable $message = null, string $propertyPath = null) Assert that an array contains at least a certain number of elements or that the value is null
 * @method static static nullOrMinLength(int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long or that the value is null
 * @method static static nullOrNatural(string|callable $message = null, string $propertyPath = null) Assert that value contains a non-negative integer or that the value is null
 * @method static static nullOrNoContent(string|callable $message = null, string $propertyPath = null) Assert that value is empty or that the value is null
 * @method static static nullOrNotBlank(string|callable $message = null, string $propertyPath = null) Assert that value is not blank or that the value is null
 * @method static static nullOrNotContains(string $needle, string|callable $message = null, string $propertyPath = null) Assert that value does not contains a substring or that the value is null
 * @method static static nullOrNotEmpty(string|callable $message = null, string $propertyPath = null) Assert that value is not empty or that the value is null
 * @method static static nullOrNotEmptyKey(string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty or that the value is null
 * @method static static nullOrNotEq(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == ) or that the value is null
 * @method static static nullOrNotInArray(array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices or that the value is null
 * @method static static nullOrNotIsInstanceOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name or that the value is null
 * @method static static nullOrNotNull(string|callable $message = null, string $propertyPath = null) Assert that value is not null or that the value is null
 * @method static static nullOrNotSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === ) or that the value is null
 * @method static static nullOrNoWhitespace(string|callable $message = null, string $propertyPath = null) Assert that value does not contains whitespace or that the value is null
 * @method static static nullOrNull(string|callable $message = null, string $propertyPath = null) Assert that value is null or that the value is null
 * @method static static nullOrNumeric(string|callable $message = null, string $propertyPath = null) Assert that value is numeric or that the value is null
 * @method static static nullOrObjectOrClass(string|callable $message = null, string $propertyPath = null) Assert that the value is an object, or a class that exists or that the value is null
 * @method static static nullOrPhpVersion(mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version or that the value is null
 * @method static static nullOrPropertiesExist(array $properties, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the properties all exist or that the value is null
 * @method static static nullOrProperty(string $name, Assertion $assert, string|callable $message = null, string $propertyPath = null) Assert a property value or that the value is null
 * @method static static nullOrPropertyExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the property exists or that the value is null
 * @method static static nullOrPropertyNotExists(string $property, string|callable $message = null, string $propertyPath = null) Assert that a property does not exist in a class/object or that the value is null
 * @method static static nullOrRange(mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers or that the value is null
 * @method static static nullOrReadable(string|callable $message = null, string $propertyPath = null) Assert that the value is something readable or that the value is null
 * @method static static nullOrRegex(string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex or that the value is null
 * @method static static nullOrSame(mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===) or that the value is null
 * @method static static nullOrSatisfy(callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback or that the value is null
 * @method static static nullOrScalar(string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar or that the value is null
 * @method static static nullOrStartsWith(string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars or that the value is null
 * @method static static nullOrString(string|callable $message = null, string $propertyPath = null) Assert that value is a string or that the value is null
 * @method static static nullOrSubclassOf(string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name or that the value is null
 * @method static static nullOrThrows(string $class, string|callable $message = null, string $propertyPath = null) Assert that a function throws a certain exception. Subclasses of the exception class will be accepted. or that the value is null
 * @method static static nullOrTrue(string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True or that the value is null
 * @method static static nullOrUpper(string|callable $message = null, string $propertyPath = null) Assert that value contains uppercase characters only or that the value is null
 * @method static static nullOrUrl(string|callable $message = null, string $propertyPath = null) Assert that value is an URL or that the value is null
 * @method static static nullOrUuid(string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID or that the value is null
 * @method static static nullOrVersion(string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions or that the value is null
 * @method static static nullOrWriteable(string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable or that the value is null
 */
class Assertion extends AllOf
{
    /**
     * @var array
     */
    protected static $ruleNamespaces = array(
        'Cubiche\\Core\\Validator\\Rules\\',
        'Cubiche\\Core\\Validator\\Rules\\Arrays\\',
        'Cubiche\\Core\\Validator\\Rules\\Comparison\\',
        'Cubiche\\Core\\Validator\\Rules\\Date\\',
        'Cubiche\\Core\\Validator\\Rules\\Generic\\',
        'Cubiche\\Core\\Validator\\Rules\\Group\\',
        'Cubiche\\Core\\Validator\\Rules\\Numeric\\',
        'Cubiche\\Core\\Validator\\Rules\\Object\\',
        'Cubiche\\Core\\Validator\\Rules\\String\\',
        'Cubiche\\Core\\Validator\\Rules\\Types\\',
        'Cubiche\\Core\\Validator\\Rules\\Web\\',
    );

    /**
     * @var array
     */
    protected static $types = array(
        'boolean',
        'float',
        'integer',
        'string',
        'null',
        'true',
        'false',
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

        foreach (static::$types as $type) {
            if (strtolower($ruleName) === $type) {
                $method = $type.'Type';

                return static::buildRule($method, $arguments);
            }
        }

        if (static::hasAssert($ruleName)) {
            return static::getAssert($ruleName);
        }

        if (0 === strpos(strtolower($ruleName), 'nullor')) {
            $method = substr($ruleName, 6);

            return new NullOr(static::buildRule($method, $arguments));
        }

        if (0 === strpos(strtolower($ruleName), 'not')) {
            $method = substr($ruleName, 3);

            return new Not(static::buildRule($method, $arguments));
        }

        if (0 === strpos(strtolower($ruleName), 'all')) {
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
