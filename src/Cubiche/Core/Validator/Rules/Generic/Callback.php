<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Generic;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Callback class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Callback extends Rule
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * Callback constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;

        parent::__construct();
    }

    /**
     * @return callable
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        if (is_array($this->callback)) {
            $ref = new \ReflectionMethod($this->callback[0], $this->callback[1]);
        } else {
            $ref = new \ReflectionFunction($this->callback);
        }

        $callbackAsString = str_replace(
            array(' ', "\n"),
            array('', ''),
            $ref->__toString()
        );

        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $callbackAsString
        );
    }
}
