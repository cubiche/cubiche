<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Cqrs\Tests\Fixtures\Query;

use Cubiche\Core\Cqrs\Query\QueryInterface;

/**
 * PublishedPostsQuery class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PublishedPostsQuery implements QueryInterface
{
    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * PublishedPostQuery constructor.
     *
     * @param \DateTime $from
     */
    public function __construct(\DateTime $from)
    {
        $this->setFrom($from);
    }

    /**
     * @return \DateTime
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     */
    public function setFrom(\DateTime $from)
    {
        $this->from = $from;
    }
}
