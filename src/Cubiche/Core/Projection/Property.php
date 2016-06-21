<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Projection;

use Cubiche\Core\Selector\SelectorInterface;

/**
 * Property Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Property
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var SelectorInterface
     */
    protected $selector;

    /**
     * @param SelectorInterface $selector
     * @param string            $name
     */
    public function __construct(SelectorInterface $selector, $name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('The property name must be non-empty');
        }

        $this->selector = $selector;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return \Cubiche\Core\Selector\SelectorInterface
     */
    public function selector()
    {
        return $this->selector;
    }
}
