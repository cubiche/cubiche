<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Console\Converter;

use Cubiche\Core\Bus\Command\CommandConverterInterface;
use Cubiche\Core\Console\Command\ConsoleCommandInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Args\Format\ArgsFormat;

/**
 * ConsoleArgsToCommand class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ConsoleArgsToCommand implements CommandConverterInterface
{
    /**
     * @var Args
     */
    protected $args;

    /**
     * @var ArgsFormat
     */
    protected $format;

    /**
     * @param Args $args
     */
    public function setArgs(Args $args)
    {
        $this->args = $args;
    }

    /**
     * @param ArgsFormat $format
     */
    public function setFormat(ArgsFormat $format)
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandFrom($className)
    {
        if (class_exists($className)) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $reflector = new \ReflectionClass($className);
            $instance = $reflector->newInstanceWithoutConstructor();

            foreach ($reflector->getProperties() as $property) {
                if ($instance instanceof ConsoleCommandInterface && $property->getName() == 'io') {
                    continue;
                }

                if (!$this->format->hasArgument($property->getName()) &&
                    !$this->format->hasOption($property->getName())
                ) {
                    throw new \InvalidArgumentException(sprintf(
                        "There is not '%s' argument defined in the %s command",
                        $property->getName(),
                        $className
                    ));
                }

                $value = null;
                if ($this->format->hasArgument($property->getName())) {
                    $value = $this->args->getArgument($property->getName());
                } elseif ($this->format->hasOption($property->getName())) {
                    $value = $this->args->getOption($property->getName());
                }

                $accessor->setValue(
                    $instance,
                    $property->getName(),
                    $value
                );
            }

            return $instance;
        }

        return;
    }
}
