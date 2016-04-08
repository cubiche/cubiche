<?php
/**
 * This file is part of the Cubiche/CommandBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Tests\Fixtures;

use Cubiche\Domain\CommandBus\MiddlewareInterface;
use Cubiche\Domain\Delegate\Delegate;

/**
 * EncoderMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EncoderMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * EncoderMiddleware constructor.
     *
     * @param string $algorithm
     */
    public function __construct($algorithm = 'md5')
    {
        $this->algorithm = $algorithm;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof LoginUserCommand) {
            $command->setPassword(call_user_func($this->algorithm, $command->password()));
        }

        $next($command);
    }
}
