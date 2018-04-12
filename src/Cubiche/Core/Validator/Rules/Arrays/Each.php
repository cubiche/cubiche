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
 * Each class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class Each extends Rule
{
    /**
     * @var Rule
     */
    protected $keyRule;

    /**
     * @var Rule
     */
    protected $valueRule;

    /**
     * Each constructor.
     *
     * @param Rule|null $keyRule
     * @param Rule|null $valueRule
     */
    public function __construct(Rule $keyRule = null, Rule $valueRule = null)
    {
        $this->keyRule = $keyRule;
        $this->valueRule = $valueRule;

        parent::__construct();
    }

    /**
     * @return Rule
     */
    public function keyRule()
    {
        return $this->keyRule;
    }

    /**
     * @return Rule
     */
    public function valueRule()
    {
        return $this->valueRule;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $this->id = sprintf(
            '%s-%s-%s',
            $this->shortClassName(),
            $this->keyRule ? $this->keyRule->id() : 'none',
            $this->valueRule ? $this->valueRule->id() : 'none'
        );
    }
}
