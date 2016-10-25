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

/**
 * VenuesQueryHandler class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class VenuesQueryHandler
{
    /**
     * @param NearbyVenuesQuery $query
     *
     * @return array
     */
    public function aroundVenues(NearbyVenuesQuery $query)
    {
        return array(
            array(
                'id' => 123,
                'title' => 'Post 1',
                'content' => 'Post 1 content',
            ),
            array(
                'id' => 345,
                'title' => 'Post 2',
                'content' => 'Post 2 content',
            ),
        );
    }
}
