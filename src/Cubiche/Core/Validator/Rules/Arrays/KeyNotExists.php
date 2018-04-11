<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Arrays;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * KeyNotExists class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class KeyNotExists extends Rule
{
    /**
     * @var string
     */
    protected $key;

    /**
     * KeyNotExists constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->key
        );
    }
}
