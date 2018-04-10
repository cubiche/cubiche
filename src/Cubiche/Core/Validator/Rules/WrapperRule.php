<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Validator\Rules;

/**
 * WrapperRule class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class WrapperRule extends Rule
{
    /**
     * @var Rule
     */
    protected $rule;

    /**
     * WrapperRule constructor.
     *
     * @param Rule $rule
     */
    public function __construct(Rule $rule)
    {
        $this->rule = $rule;

        parent::__construct();
    }

    /**
     * @return Rule
     */
    public function rule()
    {
        return $this->rule;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->shortClassName(),
            $this->rule->id()
        );
    }
}
