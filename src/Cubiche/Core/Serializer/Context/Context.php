<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Context;

use Cubiche\Core\Serializer\Visitor\VisitorInterface;
use Cubiche\Core\Serializer\Visitor\VisitorNavigatorInterface;

/**
 * Context class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class Context implements ContextInterface
{
    /**
     * @var VisitorInterface
     */
    protected $visitor;

    /**
     * @var VisitorNavigatorInterface
     */
    protected $navigator;

    /**
     * Context constructor.
     *
     * @param VisitorInterface          $visitor
     * @param VisitorNavigatorInterface $navigator
     */
    public function __construct(VisitorInterface $visitor, VisitorNavigatorInterface $navigator)
    {
        $this->visitor = $visitor;
        $this->navigator = $navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function visitor()
    {
        return $this->visitor;
    }

    /**
     * {@inheritdoc}
     */
    public function navigator()
    {
        return $this->navigator;
    }
}
