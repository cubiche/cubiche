<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\String;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Contains class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Contains extends Rule
{
    /**
     * @var string
     */
    protected $containsValue;

    /**
     * Contains constructor.
     *
     * @param string $containsValue
     */
    public function __construct($containsValue)
    {
        $this->containsValue = $containsValue;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function containsValue()
    {
        return $this->containsValue;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->containsValue
        );
    }
}
