<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Visitor;

use Cubiche\Core\Validator\Assert;
use Cubiche\Core\Validator\Exception\InvalidArgumentException;
use Cubiche\Core\Validator\Exception\InvalidArgumentsException;
use Cubiche\Core\Validator\Rules\Arrays\Count;
use Cubiche\Core\Validator\Rules\Arrays\CountBetween;
use Cubiche\Core\Validator\Rules\Arrays\Each;
use Cubiche\Core\Validator\Rules\Arrays\InArray;
use Cubiche\Core\Validator\Rules\Arrays\IsArray;
use Cubiche\Core\Validator\Rules\Arrays\IsArrayAccessible;
use Cubiche\Core\Validator\Rules\Arrays\KeyExists;
use Cubiche\Core\Validator\Rules\Arrays\KeyIsset;
use Cubiche\Core\Validator\Rules\Arrays\KeyNotExists;
use Cubiche\Core\Validator\Rules\Arrays\MaxCount;
use Cubiche\Core\Validator\Rules\Arrays\MinCount;
use Cubiche\Core\Validator\Rules\Arrays\NotEmptyKey;
use Cubiche\Core\Validator\Rules\Comparison\Eq;
use Cubiche\Core\Validator\Rules\Comparison\GreaterOrEqualThan;
use Cubiche\Core\Validator\Rules\Comparison\GreaterThan;
use Cubiche\Core\Validator\Rules\Comparison\LessOrEqualThan;
use Cubiche\Core\Validator\Rules\Comparison\LessThan;
use Cubiche\Core\Validator\Rules\Comparison\Same;
use Cubiche\Core\Validator\Rules\Date\Date;
use Cubiche\Core\Validator\Rules\Generic\All;
use Cubiche\Core\Validator\Rules\Generic\AlwaysInvalid;
use Cubiche\Core\Validator\Rules\Generic\AlwaysValid;
use Cubiche\Core\Validator\Rules\Generic\Between;
use Cubiche\Core\Validator\Rules\Generic\BetweenExclusive;
use Cubiche\Core\Validator\Rules\Generic\Callback;
use Cubiche\Core\Validator\Rules\Generic\ClassExists;
use Cubiche\Core\Validator\Rules\Generic\Defined;
use Cubiche\Core\Validator\Rules\Generic\Directory;
use Cubiche\Core\Validator\Rules\Generic\File;
use Cubiche\Core\Validator\Rules\Generic\FileExists;
use Cubiche\Core\Validator\Rules\Generic\IsCallable;
use Cubiche\Core\Validator\Rules\Generic\IsCountable;
use Cubiche\Core\Validator\Rules\Generic\IsResource;
use Cubiche\Core\Validator\Rules\Generic\Not;
use Cubiche\Core\Validator\Rules\Generic\NotBlank;
use Cubiche\Core\Validator\Rules\Generic\NullOr;
use Cubiche\Core\Validator\Rules\Generic\Regex;
use Cubiche\Core\Validator\Rules\Generic\Throws;
use Cubiche\Core\Validator\Rules\Group\AllOf;
use Cubiche\Core\Validator\Rules\Group\NoneOf;
use Cubiche\Core\Validator\Rules\Group\OneOf;
use Cubiche\Core\Validator\Rules\Numeric\Digit;
use Cubiche\Core\Validator\Rules\Numeric\Integerish;
use Cubiche\Core\Validator\Rules\Numeric\Natural;
use Cubiche\Core\Validator\Rules\Numeric\Numeric;
use Cubiche\Core\Validator\Rules\Numeric\Range;
use Cubiche\Core\Validator\Rules\Numeric\Scalar;
use Cubiche\Core\Validator\Rules\Object\ImplementsInterface;
use Cubiche\Core\Validator\Rules\Object\IsInstanceOf;
use Cubiche\Core\Validator\Rules\Object\IsInstanceOfAny;
use Cubiche\Core\Validator\Rules\Object\IsObject;
use Cubiche\Core\Validator\Rules\Object\IsTraversable;
use Cubiche\Core\Validator\Rules\Object\Method;
use Cubiche\Core\Validator\Rules\Object\MethodExists;
use Cubiche\Core\Validator\Rules\Object\MethodNotExists;
use Cubiche\Core\Validator\Rules\Object\ObjectOrClass;
use Cubiche\Core\Validator\Rules\Object\PropertiesExist;
use Cubiche\Core\Validator\Rules\Object\Property;
use Cubiche\Core\Validator\Rules\Object\PropertyExists;
use Cubiche\Core\Validator\Rules\Object\PropertyNotExists;
use Cubiche\Core\Validator\Rules\Object\SubclassOf;
use Cubiche\Core\Validator\Rules\String\Alnum;
use Cubiche\Core\Validator\Rules\String\Alpha;
use Cubiche\Core\Validator\Rules\String\Base64;
use Cubiche\Core\Validator\Rules\String\Contains;
use Cubiche\Core\Validator\Rules\String\E164;
use Cubiche\Core\Validator\Rules\String\EndsWith;
use Cubiche\Core\Validator\Rules\String\IsEmpty;
use Cubiche\Core\Validator\Rules\String\IsJsonString;
use Cubiche\Core\Validator\Rules\String\Length;
use Cubiche\Core\Validator\Rules\String\LengthBetween;
use Cubiche\Core\Validator\Rules\String\Lower;
use Cubiche\Core\Validator\Rules\String\MaxLength;
use Cubiche\Core\Validator\Rules\String\MinLength;
use Cubiche\Core\Validator\Rules\String\NotEmpty;
use Cubiche\Core\Validator\Rules\String\NoWhitespace;
use Cubiche\Core\Validator\Rules\String\Readable;
use Cubiche\Core\Validator\Rules\String\StartsWith;
use Cubiche\Core\Validator\Rules\String\Upper;
use Cubiche\Core\Validator\Rules\String\Uuid;
use Cubiche\Core\Validator\Rules\String\Writeable;
use Cubiche\Core\Validator\Rules\Types\BooleanType;
use Cubiche\Core\Validator\Rules\Types\FalseType;
use Cubiche\Core\Validator\Rules\Types\FloatType;
use Cubiche\Core\Validator\Rules\Types\IntegerType;
use Cubiche\Core\Validator\Rules\Types\NullType;
use Cubiche\Core\Validator\Rules\Types\StringType;
use Cubiche\Core\Validator\Rules\Types\TrueType;
use Cubiche\Core\Validator\Rules\Web\Email;
use Cubiche\Core\Validator\Rules\Web\Ip;
use Cubiche\Core\Validator\Rules\Web\Ipv4;
use Cubiche\Core\Validator\Rules\Web\Ipv6;
use Cubiche\Core\Validator\Rules\Web\Url;
use Cubiche\Core\Visitor\Visitee;
use Cubiche\Core\Visitor\Visitor;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Validator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Asserter extends Visitor
{
    /**
     * @param Count                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitCount(Count $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::count($input, $rule->value(), $message, $propertyPath);
    }

    /**
     * @param CountBetween         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitCountBetween(CountBetween $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::countBetween($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param Each                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitEach(Each $rule, $input, $message = null, $propertyPath = null)
    {
        Assert::isArray($input, $message, $propertyPath);

        $errors = array();
        foreach ($input as $key => $value) {
            if ($rule->keyRule() !== null) {
                try {
                    $this->visit($rule->keyRule(), $key, $message, $propertyPath);
                } catch (InvalidArgumentsException $e) {
                    $errors = static::getErrorExceptions($e, $errors);
                } catch (InvalidArgumentException $e) {
                    $errors[] = $e;
                }
            }

            if ($rule->valueRule() !== null) {
                try {
                    $this->visit($rule->valueRule(), $value, $message, $propertyPath);
                } catch (InvalidArgumentsException $e) {
                    $errors = static::getErrorExceptions($e, $errors);
                } catch (InvalidArgumentException $e) {
                    $errors[] = $e;
                }
            }
        }

        if (!empty($errors)) {
            throw InvalidArgumentsException::fromErrors($errors);
        }

        return true;
    }

    /**
     * @param InArray              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitInArray(InArray $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::inArray($input, $rule->choices(), $message, $propertyPath);
    }

    /**
     * @param IsArray              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsArray(IsArray $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isArray($input, $message, $propertyPath);
    }

    /**
     * @param IsArrayAccessible    $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsArrayAccessible(IsArrayAccessible $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isArrayAccessible($input, $message, $propertyPath);
    }

    /**
     * @param KeyExists            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitKeyExists(KeyExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::keyExists($input, $rule->key(), $message, $propertyPath);
    }

    /**
     * @param KeyIsset             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitKeyIsset(KeyIsset $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::keyIsset($input, $rule->key(), $message, $propertyPath);
    }

    /**
     * @param KeyNotExists         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitKeyNotExists(KeyNotExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::keyNotExists($input, $rule->key(), $message, $propertyPath);
    }

    /**
     * @param MaxCount             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMaxCount(MaxCount $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::maxCount($input, $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param MinCount             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMinCount(MinCount $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::minCount($input, $rule->minValue(), $message, $propertyPath);
    }

    /**
     * @param NotEmptyKey          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNotEmptyKey(NotEmptyKey $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::notEmptyKey($input, $rule->key(), $message, $propertyPath);
    }

    /**
     * @param Eq                   $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitEq(Eq $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::eq($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param GreaterOrEqualThan   $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitGreaterOrEqualThan(GreaterOrEqualThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::greaterOrEqualThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param GreaterThan          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitGreaterThan(GreaterThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::greaterThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param LessOrEqualThan      $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitLessOrEqualThan(LessOrEqualThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lessOrEqualThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param LessThan             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitLessThan(LessThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lessThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param Same                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitSame(Same $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::same($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param Date                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitDate(Date $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::date($input, $rule->format(), $message, $propertyPath);
    }

    /**
     * @param All                  $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAll(All $rule, $input, $message = null, $propertyPath = null)
    {
        Assert::isTraversable($input);

        foreach ($input as $item) {
            $this->visit($rule->rule(), $item, $message, $propertyPath);
        }

        return true;
    }

    /**
     * @param AlwaysInvalid        $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAlwaysInvalid(AlwaysInvalid $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alwaysInvalid($input, $message, $propertyPath);
    }

    /**
     * @param AlwaysValid          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAlwaysValid(AlwaysValid $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alwaysValid($input, $message, $propertyPath);
    }

    /**
     * @param Between              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitBetween(Between $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::between($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param BetweenExclusive     $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitBetweenExclusive(BetweenExclusive $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::betweenExclusive($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param Callback             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitCallback(Callback $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::callback($input, $rule->callback(), $rule->arguments(), $message, $propertyPath);
    }

    /**
     * @param ClassExists          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitClassExists(ClassExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::classExists($input, $message, $propertyPath);
    }

    /**
     * @param Defined              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitDefined(Defined $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::defined($input, $message, $propertyPath);
    }

    /**
     * @param Directory            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitDirectory(Directory $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::directory($input, $message, $propertyPath);
    }

    /**
     * @param File                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitFile(File $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::file($input, $message, $propertyPath);
    }

    /**
     * @param FileExists           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitFileExists(FileExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::fileExists($input, $message, $propertyPath);
    }

    /**
     * @param IsCallable           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsCallable(IsCallable $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isCallable($input, $message, $propertyPath);
    }

    /**
     * @param IsCountable          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsCountable(IsCountable $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isCountable($input, $message, $propertyPath);
    }

    /**
     * @param IsResource           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsResource(IsResource $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isResource($input, $rule->type(), $message, $propertyPath);
    }

    /**
     * @param Not                  $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNot(Not $rule, $input, $message = null, $propertyPath = null)
    {
        try {
            $rule->rule()->accept($this, $input, $message, $propertyPath);
        } catch (InvalidArgumentException $e) {
            return true;
        }

        $message = \sprintf(
            Assert::generateMessage( $message ?: 'Value "%s" expected to not match with the %s assert.'),
            Assert::stringify($input),
            Assert::stringify($rule->rule())
        );

        throw Assert::createException($input, $message, static::INVALID_NOT_ASSERT, $propertyPath);
    }

    /**
     * @param NotBlank             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNotBlank(NotBlank $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::notBlank($input, $message, $propertyPath);
    }

    /**
     * @param NullOr               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNullOr(NullOr $rule, $input, $message = null, $propertyPath = null)
    {
        return $this->visitOneOf(new OneOf(new NullType(), $rule->rule()), $input, $message, $propertyPath);
    }

    /**
     * @param Regex                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitRegex(Regex $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::regex($input, $rule->pattern(), $message, $propertyPath);
    }

    /**
     * @param Throws               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitThrows(Throws $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::throws($input, $rule->type(), $message, $propertyPath);
    }

    /**
     * @param AllOf                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAllOf(AllOf $rule, $input, $message = null, $propertyPath = null)
    {
        $errors = array();
        /** @var Visitee $rule */
        foreach ($rule->rules() as $rule) {
            try {
                $rule->accept($this, $input, $message, $propertyPath);
            } catch (InvalidArgumentsException $e) {
                $errors = static::getErrorExceptions($e, $errors);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e;
            }
        }

        if (!empty($errors)) {
            throw InvalidArgumentsException::fromErrors($errors);
        }

        return true;
    }

    /**
     * @param NoneOf               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNoneOf(NoneOf $rule, $input, $message = null, $propertyPath = null)
    {
        $errors = array();
        /** @var Visitee $rule */
        foreach ($rule->rules() as $rule) {
            try {
                $rule->accept($this, $input, $message, $propertyPath);

                $message = sprintf(
                    'None of these rules must pass for "%s".',
                    Assert::stringify($input)
                );

                $errors[] = new InvalidArgumentException(
                    $message,
                    Assert::INVALID_NONE_OF,
                    $propertyPath,
                    $input,
                    $rule->rules()
                );
            } catch (InvalidArgumentException $e) {
            }
        }

        if (!empty($errors)) {
            throw InvalidArgumentsException::fromErrors($errors);
        }

        return true;
    }

    /**
     * @param OneOf                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitOneOf(OneOf $rule, $input, $message = null, $propertyPath = null)
    {
        $errors = array();
        /** @var Visitee $rule */
        foreach ($rule->rules() as $rule) {
            try {
                return $rule->accept($this, $input, $message, $propertyPath);
            } catch (InvalidArgumentsException $e) {
                $errors = static::getErrorExceptions($e, $errors);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e;
            }
        }

        throw InvalidArgumentsException::fromErrors($errors);
    }

    /**
     * @param Digit                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitDigit(Digit $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::digit($input, $message, $propertyPath);
    }

    /**
     * @param Integerish           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIntegerish(Integerish $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::integerish($input, $message, $propertyPath);
    }

    /**
     * @param Natural              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNatural(Natural $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::natural($input, $message, $propertyPath);
    }

    /**
     * @param Numeric              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNumeric(Numeric $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::numeric($input, $message, $propertyPath);
    }

    /**
     * @param Scalar               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitScalar(Scalar $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::scalar($input, $message, $propertyPath);
    }

    /**
     * @param Range                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitRange(Range $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::range($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param ImplementsInterface  $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitImplementsInterface(ImplementsInterface $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::implementsInterface($input, $rule->interfaceName(), $message, $propertyPath);
    }

    /**
     * @param IsInstanceOf         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsInstanceOf(IsInstanceOf $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isInstanceOf($input, $rule->className(), $message, $propertyPath);
    }

    /**
     * @param IsInstanceOfAny      $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsInstanceOfAny(IsInstanceOfAny $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isInstanceOfAny($input, $rule->classes(), $message, $propertyPath);
    }

    /**
     * @param IsObject             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsObject(IsObject $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isObject($input, $message, $propertyPath);
    }

    /**
     * @param IsTraversable        $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsTraversable(IsTraversable $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isTraversable($input, $message, $propertyPath);
    }

    /**
     * @param Method               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMethod(Method $rule, $input, $message = null, $propertyPath = null)
    {
        Assert::methodExists($input, $rule->reference(), $propertyPath, $rule->reference());

        return $rule->validator()->accept(
            $this,
            self::getMethodValue($input, $rule->reference()),
            $message,
            $rule->reference()
        );
    }

    /**
     * @param MethodExists         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMethodExists(MethodExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::methodExists($input, $rule->methodName(), $message, $propertyPath);
    }

    /**
     * @param ObjectOrClass        $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitObjectOrClass(ObjectOrClass $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::objectOrClass($input, $message, $propertyPath);
    }

    /**
     * @param PropertiesExist      $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitPropertiesExist(PropertiesExist $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::propertiesExist($input, $rule->properties(), $message, $propertyPath);
    }

    /**
     * @param MethodNotExists      $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMethodNotExists(MethodNotExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::methodNotExists($input, $rule->methodName(), $message, $propertyPath);
    }

    /**
     * @param Property             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitProperty(Property $rule, $input, $message = null, $propertyPath = null)
    {
        Assert::propertyExists($input, $rule->reference(), $propertyPath, $rule->reference());

        return $rule->validator()->accept(
            $this,
            self::getPropertyValue($input, $rule->reference()),
            $message,
            $rule->reference()
        );
    }

    /**
     * @param PropertyExists       $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitPropertyExists(PropertyExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::propertyExists($input, $rule->propertyName(), $message, $propertyPath);
    }

    /**
     * @param PropertyNotExists    $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitPropertyNotExists(PropertyNotExists $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::propertyNotExists($input, $rule->propertyName(), $message, $propertyPath);
    }

    /**
     * @param SubclassOf           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitSubclassOf(SubclassOf $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::subclassOf($input, $rule->className(), $message, $propertyPath);
    }

    /**
     * @param Alnum                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAlnum(Alnum $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alnum($input, $message, $propertyPath);
    }

    /**
     * @param Alpha                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitAlpha(Alpha $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alpha($input, $message, $propertyPath);
    }

    /**
     * @param Base64               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitBase64(Base64 $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::base64($input, $message, $propertyPath);
    }

    /**
     * @param Contains             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitContains(Contains $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::contains($input, $rule->needle(), $message, $propertyPath);
    }

    /**
     * @param E164                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitE164(E164 $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::e164($input, $message, $propertyPath);
    }

    /**
     * @param EndsWith             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitEndsWith(EndsWith $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::endsWith($input, $rule->needle(), $message, $propertyPath);
    }

    /**
     * @param IsEmpty              $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsEmpty(IsEmpty $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isEmpty($input, $message, $propertyPath);
    }

    /**
     * @param IsJsonString         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIsJsonString(IsJsonString $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::isJsonString($input, $message, $propertyPath);
    }

    /**
     * @param Length               $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitLength(Length $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::length($input, $rule->length(), $message, $propertyPath);
    }

    /**
     * @param LengthBetween        $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitLengthBetween(LengthBetween $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lengthBetween($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param Lower                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitLower(Lower $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lower($input, $message, $propertyPath);
    }

    /**
     * @param MaxLength            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMaxLength(MaxLength $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::maxLength($input, $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param MinLength            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitMinLength(MinLength $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::minLength($input, $rule->minValue(), $message, $propertyPath);
    }

    /**
     * @param NotEmpty             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNotEmpty(NotEmpty $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::notEmpty($input, $message, $propertyPath);
    }

    /**
     * @param NoWhitespace         $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNoWhitespace(NoWhitespace $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::noWhitespace($input, $message, $propertyPath);
    }

    /**
     * @param Readable             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitReadable(Readable $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::readable($input, $message, $propertyPath);
    }

    /**
     * @param StartsWith           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitStartsWith(StartsWith $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::startsWith($input, $rule->needle(), $message, $propertyPath);
    }

    /**
     * @param Upper                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitUpper(Upper $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::upper($input, $message, $propertyPath);
    }

    /**
     * @param Uuid                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitUuid(Uuid $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::uuid($input, $message, $propertyPath);
    }

    /**
     * @param Writeable            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitWriteable(Writeable $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::writeable($input, $message, $propertyPath);
    }

    /**
     * @param BooleanType          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitBooleanType(BooleanType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::boolean($input, $message, $propertyPath);
    }

    /**
     * @param FalseType            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitFalseType(FalseType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::false($input, $message, $propertyPath);
    }

    /**
     * @param FloatType            $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitFloatType(FloatType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::float($input, $message, $propertyPath);
    }

    /**
     * @param IntegerType          $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIntegerType(IntegerType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::integer($input, $message, $propertyPath);
    }

    /**
     * @param NullType             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitNullType(NullType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::null($input, $message, $propertyPath);
    }

    /**
     * @param StringType           $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitStringType(StringType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::string($input, $message, $propertyPath);
    }

    /**
     * @param TrueType             $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitTrueType(TrueType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::true($input, $message, $propertyPath);
    }

    /**
     * @param Email                $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitEmail(Email $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::email($input, $message, $propertyPath);
    }

    /**
     * @param Ip                   $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIp(Ip $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::ip($input, $rule->flag(), $message, $propertyPath);
    }

    /**
     * @param Ipv4                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIpv4(Ipv4 $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::ipv4($input, $rule->flag(), $message, $propertyPath);
    }

    /**
     * @param Ipv6                 $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitIpv6(Ipv6 $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::ipv6($input, $rule->flag(), $message, $propertyPath);
    }

    /**
     * @param Url                  $rule
     * @param mixed                $input
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function visitUrl(Url $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::url($input, $message, $propertyPath);
    }

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     */
    protected static function getPropertyValue($object, $propertyName)
    {
        $propertyMirror = new ReflectionProperty($object, $propertyName);
        $propertyMirror->setAccessible(true);

        return $propertyMirror->getValue($object);
    }

    /**
     * @param object $object
     * @param string $methodName
     *
     * @return mixed
     */
    protected static function getMethodValue($object, $methodName)
    {
        $methodMirror = new ReflectionMethod($object, $methodName);
        $methodMirror->setAccessible(true);

        return $methodMirror->invoke($object);
    }

    /**
     * @param InvalidArgumentsException $exception
     * @param array                     $errors
     *
     * @return array
     */
    public static function getErrorExceptions(InvalidArgumentsException $exception, $errors = array())
    {
        foreach ($exception->getErrorExceptions() as $errorException) {
            if ($errorException instanceof InvalidArgumentsException) {
                $errors = array_merge($errors, static::getErrorExceptions($errorException));
            } else {
                $errors[] = $errorException;
            }
        }

        return $errors;
    }
}
