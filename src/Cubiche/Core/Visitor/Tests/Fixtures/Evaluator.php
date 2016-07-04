<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Fixtures;

use Cubiche\Core\Visitor\LinkedVisitor;

/**
 * Evaluator Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Evaluator extends LinkedVisitor
{
    /**
     * @var array
     */
    protected $variables;

    /**
     * @param Calculator $calculator
     * @param array      $variables
     */
    public function __construct(Calculator $calculator, $variables = array())
    {
        parent::__construct($calculator);

        $this->variables = $variables;
    }

    /**
     * @param Variable $variable
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function visitVariable(Variable $variable)
    {
        if (isset($this->variables[$variable->name()])) {
            return $this->variables[$variable->name()];
        }

        throw new \Exception(\sprintf("Unknown variable '%s'", $variable->name()));
    }
}
