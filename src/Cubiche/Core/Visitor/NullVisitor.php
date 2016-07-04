<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor;

/**
 * Null Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
final class NullVisitor extends LinkedVisitor
{
    /**
     * @var NullVisitor
     */
    private static $instance = null;

    /**
     * @return \Cubiche\Core\Visitor\NullVisitor
     */
    public static function create()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function canHandlerVisitee(VisiteeInterface $visitee)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisit(VisiteeInterface $visitee, array $args, $fromParent = false)
    {
        throw $this->notSupportedVisiteeException($visitee);
    }
}
