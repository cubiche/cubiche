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

use Cubiche\Core\Serializer\Context\ContextInterface;

/**
 * VisitorNavigator interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface VisitorNavigatorInterface
{
    /**
     * @param mixed            $data
     * @param array|null       $type
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function accept($data, array $type = null, ContextInterface $context);
}
