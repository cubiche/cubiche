<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification;

use Cubiche\Core\Delegate\AbstractCallable;
use Cubiche\Core\Visitor\VisiteeTrait;

/**
 * Abstract Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class Specification extends AbstractCallable implements SpecificationInterface
{
    use VisiteeTrait;
    use SpecificationTrait;

    /**
     * {@inheritdoc}
     */
    protected function innerCallable()
    {
        return array($this, 'evaluate');
    }
}
