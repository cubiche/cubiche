<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Object;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * IsInstanceOfAny class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class IsInstanceOfAny extends Rule
{
    /**
     * @var array
     */
    protected $classes;

    /**
     * IsInstanceOfAny constructor.
     *
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $this->classes = $classes;

        parent::__construct();
    }

    /**
     * @return array
     */
    public function classes()
    {
        return $this->classes;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        sort($this->classes);

        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            json_encode($this->classes)
        );
    }
}
