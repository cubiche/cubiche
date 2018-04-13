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
 * InArray class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InArray extends Rule
{
    /**
     * @var array
     */
    protected $choices;

    /**
     * InArray constructor.
     *
     * @param array $choices
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;

        parent::__construct();
    }

    /**
     * @return array
     */
    public function choices()
    {
        return $this->choices;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        sort($this->choices);

        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->choices)
        );
    }
}
