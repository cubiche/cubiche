<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;

/**
 * AbstractAnnotationDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class AbstractAnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * AbstractAnnotationDriver constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
}
