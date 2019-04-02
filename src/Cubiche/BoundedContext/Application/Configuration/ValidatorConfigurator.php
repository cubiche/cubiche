<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\BoundedContext\Application\Configuration;

use Cubiche\Core\Validator\Validator;
use Cubiche\Domain\Geolocation\Validator\Asserter as GeolocationAsserter;
use Cubiche\Domain\Locale\Validator\Asserter as LocaleAsserter;
use Psr\Container\ContainerInterface;
use function DI\get;

/**
 * ValidatorConfigurator class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class ValidatorConfigurator implements ConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function configuration(): array
    {
        return [
            // binding validators
            'app.validator.asserter.geolocation' => function () {
                return new GeolocationAsserter();
            },
            'app.validator.asserter.locale' => function () {
                return new LocaleAsserter();
            },
            'app.validator.asserters' => [
                'distanceUnit' => get('app.validator.asserter.geolocation'),
                'countryCode' => get('app.validator.asserter.locale'),
                'languageCode' => get('app.validator.asserter.locale'),
                'localeCode' => get('app.validator.asserter.locale'),
            ],
            'app.validator' => function (ContainerInterface $container) {
                $validator = Validator::create();

                // configure validator asserters
                $validators = $container->get('app.validator.asserters');
                foreach ($validators as $assertName => $handler) {
                    $validator->registerValidator($assertName, array($handler, $assertName));
                }

                return $validator;
            }
        ];
    }
}
