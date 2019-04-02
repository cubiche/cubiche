<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Exception;

/**
 * Exception class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class Exception extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var string
     */
    private $statusCode;

    /**
     * Exception constructor.
     *
     * @param string $statusCode
     * @param null   $message
     */
    public function __construct($statusCode, $message = null)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $statusCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
