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
 * Visitor Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Visitor implements VisitorInterface
{
    /**
     * {@inheritdoc}
     */
    public function visit(VisiteeInterface $visitee)
    {
        throw $this->notSupportedVisiteeException($visitee);
    }

    /**
     * @param VisiteeInterface $visitee
     *
     * @return \LogicException
     */
    protected function notSupportedVisiteeException(VisiteeInterface $visitee)
    {
        return new \LogicException(
            \sprintf('The %s visitee is not supported by %s visitor', \get_class($visitee), static::class)
        );
    }
}
