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
class InvalidArgumentsException extends InvalidArgumentException
{
    /**
     * @var InvalidArgumentException[]
     */
    protected $errors;

    /**
     * @param string   $message
     * @param string[] $errors
     */
    public function __construct($message, array $errors = [])
    {
        parent::__construct($message, 422, null, null);

        $this->errors = $errors;
    }

    /**
     * @param InvalidArgumentException[] $errors
     *
     * @return self
     */
    public static function fromErrors(array $errors)
    {
        $message = sprintf('The following %d assertions failed:', count($errors))."\n";
        $i = 1;
        foreach ($errors as $error) {
            $message .= sprintf("%d) %s: %s\n", $i++, $error->getPropertyPath(), $error->getMessage());
        }

        return new static($message, $errors);
    }

    /**
     * @return InvalidArgumentException[]
     */
    public function getErrorExceptions()
    {
        return $this->errors;
    }
}
