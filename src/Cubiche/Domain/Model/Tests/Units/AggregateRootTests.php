<?php

/**
 * This file is part of the Cubiche/Model component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\Tests\Units;

use Cubiche\Domain\Model\Tests\Fixtures\Post;

/**
 * AggregateRootTests class.
 *
 * Generated by TestGenerator on 2016-05-03 at 16:01:26.
 */
class AggregateRootTests extends TestCase
{
    /**
     * Test recordApplyAndPublishEvent method.
     */
    public function testRecordApplyAndPublishEvent()
    {
        $this
            ->given(
                $post = Post::create(
                    $this->faker->sentence,
                    $this->faker->paragraph
                )
            )
            ->then()
                ->array($post->recordedEvents())
                    ->hasSize(1)
        ;
    }

    /**
     * Test ClearEvents method.
     */
    public function testClearEvents()
    {
        // todo: Implement testClearEvents().
    }

    /**
     * Test LoadFromHistory method.
     */
    public function testLoadFromHistory()
    {
        // todo: Implement testLoadFromHistory().
    }

    /**
     * Test Replay method.
     */
    public function testReplay()
    {
        // todo: Implement testReplay().
    }
}
