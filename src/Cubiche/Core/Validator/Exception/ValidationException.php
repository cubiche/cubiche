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
     * @param string   $message
     * @param string[] $errors
     */
    public function __construct($message, array $errors = [])
    {
        parent::__construct($message, 422);

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
