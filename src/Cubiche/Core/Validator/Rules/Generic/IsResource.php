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
 * IsResource class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IsResource extends Rule
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * IsResource constructor.
     *
     * @param string|null $type
     */
    public function __construct($type = null)
    {
        $this->type = $type;

        parent::__construct();
    }

    /**
     * @return mixed
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
            json_encode($this->type)
        );
    }
}
