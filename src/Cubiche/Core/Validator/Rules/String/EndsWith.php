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
 * EndsWith class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EndsWith extends Rule
{
    /**
     * @var string
     */
    protected $needle;

    /**
     * Contains constructor.
     *
     * @param string $needle
     */
    public function __construct($needle)
    {
        $this->needle = $needle;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function needle()
    {
        return $this->needle;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->needle
        );
    }
}
