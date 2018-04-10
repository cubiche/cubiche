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
use Cubiche\Core\Validator\Rules\Common\NotBlank;
use Cubiche\Core\Validator\Rules\Comparison\GreaterOrEqualThan;
use Cubiche\Core\Validator\Rules\Comparison\GreaterThan;
use Cubiche\Core\Validator\Rules\Comparison\LessOrEqualThan;
use Cubiche\Core\Validator\Rules\Comparison\LessThan;
use Cubiche\Core\Validator\Rules\Generic\All;
use Cubiche\Core\Validator\Rules\Generic\AlwaysInvalid;
use Cubiche\Core\Validator\Rules\Generic\AlwaysValid;
use Cubiche\Core\Validator\Rules\Generic\Callback;
use Cubiche\Core\Validator\Rules\Generic\Not;
use Cubiche\Core\Validator\Rules\Generic\NullOr;
use Cubiche\Core\Validator\Rules\Generic\NullType;
use Cubiche\Core\Validator\Rules\Group\AllOf;
use Cubiche\Core\Validator\Rules\Group\NoneOf;
use Cubiche\Core\Validator\Rules\Group\OneOf;
use Cubiche\Core\Validator\Rules\Numeric\FloatType;
use Cubiche\Core\Validator\Rules\Numeric\IntegerType;
use Cubiche\Core\Validator\Rules\Object\Method;
use Cubiche\Core\Validator\Rules\Object\Property;
use Cubiche\Core\Validator\Rules\String\Alnum;
use Cubiche\Core\Validator\Rules\String\Alpha;
use Cubiche\Core\Validator\Rules\String\Contains;
use Cubiche\Core\Validator\Rules\String\Length;
use Cubiche\Core\Validator\Rules\String\StringType;
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
     * @param NotBlank $rule
     * @param mixed    $input
     * @param null     $message
     * @param null     $propertyPath
     *
     * @return bool
     */
    public function visitNotBlank(NotBlank $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::notBlank($input, $message, $propertyPath);
    }

    /**
     * @param GreaterOrEqualThan $rule
     * @param mixed              $input
     * @param null               $message
     * @param null               $propertyPath
     *
     * @return bool
     */
    public function visitGreaterOrEqualThan(GreaterOrEqualThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::greaterOrEqualThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param GreaterThan $rule
     * @param mixed       $input
     * @param null        $message
     * @param null        $propertyPath
     *
     * @return bool
     */
    public function visitGreaterThan(GreaterThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::greaterThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param LessOrEqualThan $rule
     * @param mixed           $input
     * @param null            $message
     * @param null            $propertyPath
     *
     * @return bool
     */
    public function visitLessOrEqualThan(LessOrEqualThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lessOrEqualThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param LessThan $rule
     * @param mixed    $input
     * @param null     $message
     * @param null     $propertyPath
     *
     * @return bool
     */
    public function visitLessThan(LessThan $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::lessThan($input, $rule->other(), $message, $propertyPath);
    }

    /**
     * @param All   $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
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
     * @param AlwaysInvalid $rule
     * @param mixed         $input
     * @param null          $message
     * @param null          $propertyPath
     *
     * @return bool
     */
    public function visitAlwaysInvalid(AlwaysInvalid $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alwaysInvalid($input, $message, $propertyPath);
    }

    /**
     * @param AlwaysValid $rule
     * @param mixed       $input
     * @param null        $message
     * @param null        $propertyPath
     *
     * @return bool
     */
    public function visitAlwaysValid(AlwaysValid $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alwaysValid($input, $message, $propertyPath);
    }

    /**
     * @param Callback $rule
     * @param mixed    $input
     * @param null     $message
     * @param null     $propertyPath
     *
     * @return bool
     */
    public function visitCallback(Callback $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::satisfy($input, $rule->callback(), $message, $propertyPath);
    }

    /**
     * @param Not   $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function visitNot(Not $rule, $input, $message = null, $propertyPath = null)
    {
        try {
            $rule->rule()->accept($this, $input, $message, $propertyPath);

            throw new InvalidArgumentException($message, 10, $propertyPath, $input);
        } catch (InvalidArgumentException $e) {
            return true;
        }
    }

    /**
     * @param NullOr $rule
     * @param mixed  $input
     * @param null   $message
     * @param null   $propertyPath
     *
     * @return bool
     */
    public function visitNullOr(NullOr $rule, $input, $message = null, $propertyPath = null)
    {
        return $this->visitOneOf(new OneOf(new NullType(), $rule->rule()), $input, $message, $propertyPath);
    }

    /**
     * @param NullType $rule
     * @param mixed    $input
     * @param null     $message
     * @param null     $propertyPath
     *
     * @return bool
     */
    public function visitNullType(NullType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::null($input, $message, $propertyPath);
    }

    /**
     * @param AllOf $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
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
     * @param NoneOf $rule
     * @param mixed  $input
     * @param null   $message
     * @param null   $propertyPath
     *
     * @return bool
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

                $errors[] = new InvalidArgumentException($message, Assert::INVALID_NONE_OF, $propertyPath, $input, $rule->rules());
            } catch (InvalidArgumentException $e) {
            }
        }

        if (!empty($errors)) {
            throw InvalidArgumentsException::fromErrors($errors);
        }

        return true;
    }

    /**
     * @param OneOf $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
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
     * @param FloatType $rule
     * @param mixed     $input
     * @param null      $message
     * @param null      $propertyPath
     *
     * @return bool
     */
    public function visitFloatType(FloatType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::float($input, $message, $propertyPath);
    }

    /**
     * @param IntegerType $rule
     * @param mixed       $input
     * @param null        $message
     * @param null        $propertyPath
     *
     * @return bool
     */
    public function visitIntegerType(IntegerType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::integer($input, $message, $propertyPath);
    }

    /**
     * @param Method $rule
     * @param mixed  $input
     * @param null   $message
     *
     * @return mixed
     */
    public function visitMethod(Method $rule, $input, $message = null)
    {
        Assert::methodExists($rule->reference(), $input, null, $rule->reference());

        return $rule->validator()->accept(
            $this,
            self::getMethodValue($input, $rule->reference()),
            $message,
            $rule->reference()
        );
    }

    /**
     * @param Property $rule
     * @param mixed    $input
     * @param null     $message
     *
     * @return mixed
     */
    public function visitProperty(Property $rule, $input, $message = null)
    {
        Assert::propertyExists($input, $rule->reference(), null, $rule->reference());

        return $rule->validator()->accept(
            $this,
            self::getPropertyValue($input, $rule->reference()),
            $message,
            $rule->reference()
        );
    }

    /**
     * @param Alnum $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function visitAlnum(Alnum $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alnum($input, $message, $propertyPath);
    }

    /**
     * @param Alpha $rule
     * @param mixed $input
     * @param null  $message
     * @param null  $propertyPath
     *
     * @return bool
     */
    public function visitAlpha(Alpha $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::alpha($input, $message, $propertyPath);
    }

    /**
     * @param Contains $rule
     * @param mixed    $input
     * @param null     $message
     * @param null     $propertyPath
     *
     * @return bool
     */
    public function visitContains(Contains $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::contains($input, $rule->containsValue(), $message, $propertyPath);
    }

    /**
     * @param Length $rule
     * @param mixed  $input
     * @param null   $message
     * @param null   $propertyPath
     *
     * @return bool
     */
    public function visitLength(Length $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::betweenLength($input, $rule->minValue(), $rule->maxValue(), $message, $propertyPath);
    }

    /**
     * @param StringType $rule
     * @param mixed      $input
     * @param null       $message
     * @param null       $propertyPath
     *
     * @return bool
     */
    public function visitStringType(StringType $rule, $input, $message = null, $propertyPath = null)
    {
        return Assert::string($input, $message, $propertyPath);
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
