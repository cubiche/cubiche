<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Bundle\CoreBundle\DependencyInjection\Compiler;

use Cubiche\Core\Validator\Assert;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * RegisterValidatorAsserterPass class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class RegisterValidatorAsserterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('cubiche.validator.asserter');
        if (count($taggedServices) > 0 && $container->hasDefinition('cubiche.validator')) {
            $validator = $container->getDefinition('cubiche.validator');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    Assert::keyExists($attributes, 'assert');

                    $validator->addMethodCall(
                        'registerValidator',
                        array($attributes['assert'], array(new Reference($id), $attributes['assert']))
                    );
                }
            }
        }
    }
}
