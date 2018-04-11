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
 * Throws class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Throws extends Rule
{
    /**
     * @var string
     */
    protected $type;

    /**
     * IsResource constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'Exception')
    {
        $this->type = $type;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->type
        );
    }
}
