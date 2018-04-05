<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Visitor;

use Cubiche\Core\Visitor\Visitor;

/**
 * AbstractVisitor class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractVisitor extends Visitor implements VisitorInterface
{
    /**
     * @var VisitorNavigatorInterface
     */
    protected $navigator;

    /**
     * SerializationVisitor constructor.
     *
     * @param VisitorNavigatorInterface $navigator
     */
    public function __construct(VisitorNavigatorInterface $navigator)
    {
        $this->navigator = $navigator;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isNull($value)
    {
        return $value === null;
    }
}
