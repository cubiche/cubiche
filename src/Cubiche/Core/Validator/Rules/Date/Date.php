<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules\Date;

use Cubiche\Core\Validator\Rules\Rule;

/**
 * Date class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Date extends Rule
{
    /**
     * @var string
     */
    protected $format;

    /**
     * KeyIsset constructor.
     *
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function format()
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->format
        );
    }
}
