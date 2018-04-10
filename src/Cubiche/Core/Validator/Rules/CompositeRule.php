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

use Cubiche\Core\Collections\ArrayCollection\ArrayList;

/**
 * CompositeRule class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class CompositeRule extends Rule
{
    /**
     * @var ArrayList
     */
    protected $rules;

    /**
     * CompositeRule constructor.
     */
    public function __construct()
    {
        $this->rules = new ArrayList();
        $this->addRules(func_get_args());
    }

    /**
     * @return ArrayList
     */
    public function rules()
    {
        return $this->rules->toArray();
    }

    /**
     * @param Rule $rule
     *
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        $this->rules->add($rule);
        $this->setId();

        return $this;
    }

    /**
     * @param array $rules
     *
     * @return $this
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function setId()
    {
        $ids = array();
        /** @var Rule $rule */
        foreach ($this->rules as $rule) {
            $ids[] = $rule->id();
        }
        sort($ids);

        $this->id = sprintf('%s-%s', $this->shortClassName(), implode('-', $ids));
    }
}
