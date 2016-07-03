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
 * Abstract Linked Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class LinkedVisitor extends DynamicDispatchVisitor
{
    /**
     * @var LinkedVisitor
     */
    private $next = null;

    /**
     * @var LinkedVisitor
     */
    private $parent = null;

    /**
     * @param LinkedVisitor                  $next
     * @param ResolverVisitorMethodInterface $resolver
     */
    public function __construct(
        LinkedVisitor $next = null,
        ResolverVisitorMethodInterface $resolver = null
    ) {
        $this->next = $next === null ? NullVisitor::create() : $next;
        $this->next->parent = $this;
        $this->parent = null;
        $this->resolver = $resolver === null ? new ResolverVisitorMethod() : $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandlerVisitee(VisiteeInterface $visitee)
    {
        return parent::canHandlerVisitee($visitee) || $this->next->canHandlerVisitee($visitee);
    }

    /**
     * {@inheritdoc}
     */
    public function visit(VisiteeInterface $visitee)
    {
        return $this->doVisit($visitee, \func_get_args());
    }

    /**
     * @param VisiteeInterface $visitee
     * @param array            $args
     * @param bool             $fromParent
     */
    protected function doVisit(VisiteeInterface $visitee, array $args, $fromParent = false)
    {
        if (!$fromParent && $this->parent !== null) {
            return \call_user_func_array(array($this->parent, 'visit'), $args);
        }

        if (parent::canHandlerVisitee($visitee)) {
            return $this->visitWith($visitee, $args);
        }

        return $this->next->doVisit($visitee, $args, true);
    }
}
