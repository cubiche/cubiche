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
 * Regex class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Regex extends Rule
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * Contains constructor.
     *
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function pattern()
    {
        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->pattern
        );
    }
}
