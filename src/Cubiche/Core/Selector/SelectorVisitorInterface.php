<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Selector;

use Cubiche\Core\Visitor\VisitorInterface;

/**
 * Selector Visitor Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface SelectorVisitorInterface extends VisitorInterface
{
    /**
     * @param Value $value
     *
     * @return mixed
     */
    public function visitValue(Value $value);

    /**
     * @param Key $key
     *
     * @return mixed
     */
    public function visitKey(Key $key);

    /**
     * @param Property $property
     *
     * @return mixed
     */
    public function visitProperty(Property $property);

    /**
     * @param Method $method
     *
     * @return mixed
     */
    public function visitMethod(Method $method);

    /**
     * @param This $self
     *
     * @return mixed
     */
    public function visitThis(This $self);

    /**
     * @param Custom $custom
     *
     * @return mixed
     */
    public function visitCustom(Custom $custom);

    /**
     * @param Count $count
     *
     * @return mixed
     */
    public function visitCount(Count $count);

    /**
     * @param Composite $composite
     *
     * @return mixed
     */
    public function visitComposite(Composite $composite);
}
