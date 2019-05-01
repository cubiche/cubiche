<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Infrastructure\Console;

use Cubiche\MicroService\Application\BoundedContextInterface;
use Cubiche\Core\Validator\Exception\ValidationException;
use Cubiche\MicroService\Application\Controllers\CommandController;
use Cubiche\MicroService\Application\Controllers\QueryController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BoundedContextAwareCommand class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class BoundedContextAwareCommand extends Command
{
    /**
     * @var BoundedContextInterface
     */
    protected $context;

    /**
     * @param BoundedContextInterface|null $context
     */
    public function setBoundedContext(BoundedContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param $command
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return $this
     *
     * @throws \Exception
     */
    protected function runCommand($command, InputInterface $input, OutputInterface $output)
    {
        $this
            ->getApplication()
            ->find($command)
            ->run($input, $output)
        ;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return CommandController
     */
    public function commandController($name)
    {
        return $this->context->commandController($name);
    }

    /**
     * @param string $name
     *
     * @return QueryController
     */
    public function queryController($name)
    {
        return $this->context->queryController($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->context->getParameter($name);
    }

    /**
     * @param ValidationException $e
     * @param OutputInterface     $output
     */
    protected function printValidationErrors(ValidationException $e, OutputInterface $output)
    {
        $i = 1;
        foreach ($e->getErrorExceptions() as $error) {
            $output->writeln(
                '<error>'.sprintf('%d) %s: %s', $i++, $error->getPropertyPath(), $error->getMessage()).'</error>'
            );
        }
    }
}
