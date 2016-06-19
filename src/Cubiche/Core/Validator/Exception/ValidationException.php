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
    protected $messages;

    /**
     * @param string     $message
     * @param string[]   $messages
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message, array $messages = [], $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->messages = $messages;
    }

    /**
     * @return string[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
