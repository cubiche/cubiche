<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Specification;

use Cubiche\Domain\Specification\Selector\Composite;
use Cubiche\Domain\Specification\Selector\Count;
use Cubiche\Domain\Specification\Selector\Custom;
use Cubiche\Domain\Specification\Selector\Key;
use Cubiche\Domain\Specification\Selector\Method;
use Cubiche\Domain\Specification\Selector\Property;
use Cubiche\Domain\Specification\Selector\This;
use Cubiche\Domain\Specification\Selector\Value;

/**
 * Selector Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SelectorVisitorInterface
{
    /**
     * @param Value $specification
     *
     * @return mixed
     */
    public function visitValue(Value $specification);

    /**
     * @param Key $specification
     *
     * @return mixed
     */
    public function visitKey(Key $specification);

    /**
     * @param Property $specification
     *
     * @return mixed
     */
    public function visitProperty(Property $specification);

    /**
     * @param Method $specification
     *
     * @return mixed
     */
    public function visitMethod(Method $specification);

    /**
     * @param This $specification
     *
     * @return mixed
     */
    public function visitThis(This $specification);

    /**
     * @param Custom $specification
     *
     * @return mixed
     */
    public function visitCustom(Custom $specification);

    /**
     * @param Count $specification
     *
     * @return mixed
     */
    public function visitCount(Count $specification);

    /**
     * @param Composite $specification
     *
     * @return mixed
     */
    public function visitComposite(Composite $specification);
}
