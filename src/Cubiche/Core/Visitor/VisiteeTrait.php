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
 * Visitee trait.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
trait VisiteeTrait
{
    /**
     * @param VisitorInterface $visitor
     *
     * @return mixed
     */
    public function accept(VisitorInterface $visitor)
    {
        return $this->delegateAccept($this, $visitor, \func_get_args());
    }

    /**
     * @param VisiteeInterface $visitee
     * @param VisitorInterface $visitor
     * @param array            $args
     *
     * @return mixed
     */
    protected function delegateAccept(VisiteeInterface $visitee, VisitorInterface $visitor, array $args)
    {
        $args[0] = $visitee;

        return \call_user_func_array(array($visitor, 'visit'), $args);
    }
}
