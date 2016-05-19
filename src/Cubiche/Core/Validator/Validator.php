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
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Validator implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected static $instance = null;

    /**
     * @var Constraints
     */
    protected $constraints;

    /**
     * Validator constructor.
     */
    private function __construct()
    {
        $this->constraints = Assert::create();
    }

    /**
     * {@inheritdoc}
     */
    public function constraints()
    {
        return $this->constraints;
    }

    /**
     * @param Assert $assert
     *
     * @return $this
     */
    public function addConstraint(Assert $assert)
    {
        $this->constraints->removeRules();
        $this->constraints->addRules($assert->getRules());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function assert($value)
    {
        try {
            $returnValue = $this->constraints->assert($value);
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

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        return $this->constraints->validate($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function create()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        static::$instance->constraints->removeRules();

        return static::$instance;
    }
}
