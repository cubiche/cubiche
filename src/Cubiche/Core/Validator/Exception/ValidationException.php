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
 * ValidationException class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ValidationException extends \InvalidArgumentException
{
    /**
     * @var string[]
     */
    protected $errors;

    /**
     * @param string     $message
     * @param string[]   $errors
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message, array $errors = [], $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
