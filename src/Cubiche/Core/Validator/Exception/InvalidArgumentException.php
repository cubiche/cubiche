<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Exception;

/**
 * InvalidArgumentException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    protected $propertyPath;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $constraints;

    /**
     * InvalidArgumentException constructor.
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $propertyPath
     * @param mixed      $value
     * @param array      $constraints
     */
    public function __construct($message, $code, $propertyPath, $value, array $constraints = array())
    {
        parent::__construct($message, $code);

        $this->propertyPath = $propertyPath;
        $this->value = $value;
        $this->constraints = $constraints;
    }

    /**
     * User controlled way to define a sub-property causing
     * the failure of a currently asserted objects.
     *
     * Useful to transport information about the nature of the error
     * back to higher layers.
     *
     * @return string
     */
    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    /**
     * Get the value that caused the assertion to fail.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the constraints that applied to the failed assertion.
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }
}
