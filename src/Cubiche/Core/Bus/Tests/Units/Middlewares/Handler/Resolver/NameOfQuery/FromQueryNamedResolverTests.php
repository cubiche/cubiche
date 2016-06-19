<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Resolver\NameOfQuery;

use Cubiche\Core\Bus\Middlewares\Handler\Resolver\NameOfQuery\FromQueryNamedResolver;
use Cubiche\Core\Bus\Tests\Fixtures\Query\NearbyVenuesQuery;
use Cubiche\Core\Bus\Tests\Fixtures\Query\PublishedPostsQuery;
use Cubiche\Core\Bus\Tests\Units\TestCase;

/**
 * FromQueryNamedResolverTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class FromQueryNamedResolverTests extends TestCase
{
    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given($resolver = new FromQueryNamedResolver())
            ->when(
                $result = $resolver->resolve(
                    new NearbyVenuesQuery($this->faker->latitude(), $this->faker->longitude())
                )
            )
            ->then()
                ->string($result)
                    ->isEqualTo('nearby_venues')
        ;

        $this
            ->given($resolver = new FromQueryNamedResolver())
            ->then()
                ->exception(function () use ($resolver) {
                    $resolver->resolve(new PublishedPostsQuery(new \DateTime()));
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
