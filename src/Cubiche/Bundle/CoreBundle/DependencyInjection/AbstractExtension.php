<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AbstractExtension class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AbstractExtension extends Extension
{
    /**
     * @var string
     */
    const CONFIG_FORMAT = 'xml';

    /**
     * @var string
     */
    protected $servicesDirectory = '/../Resources/config/services';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator($this->getServicesDirectory()));

        $finder = new Finder();
        foreach ($finder->files()->in($this->getServicesDirectory()) as $file) {
            if (file_exists($file->getRealPath())) {
                $loader->load($file->getRealPath());
            }
        }

        $this->configure($configs, $container);
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function configure(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * Get the services configuration directory.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getServicesDirectory()
    {
        $reflector = new \ReflectionClass($this);
        $fileName = $reflector->getFileName();

        if (!is_dir($directory = dirname($fileName).$this->servicesDirectory)) {
            throw new \RuntimeException(
                sprintf('The services configuration directory "%s" does not exists.', $directory)
            );
        }

        return realpath($directory);
    }
}
