<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use \mageekguy\atoum\reports\asynchronous\coveralls;
use \mageekguy\atoum\visibility\extension;

$script->addDefaultReport();

if (getenv('TRAVIS_PHP_VERSION') === '7.0') {
    $script
        ->php('php -n -ddate.timezone=Europe/Madrid')
        ->noCodeCoverage()
    ;
} else {
    if ($token = getenv('COVERALLS_REPO_TOKEN')) {
        $coverallsReport = new coveralls(__DIR__.'/src', $token);

        $defaultFinder = $coverallsReport->getBranchFinder();
        $coverallsReport
            ->setBranchFinder(function () use ($defaultFinder) {
                if (($branch = getenv('TRAVIS_BRANCH')) === false) {
                    $branch = $defaultFinder();
                }

                return $branch;
            })
            ->setServiceName(getenv('TRAVIS') ? 'travis-ci' : null)
            ->setServiceJobId(getenv('TRAVIS_JOB_ID') ?: null)
            ->addDefaultWriter()
        ;

        $runner->addReport($coverallsReport);
        $runner->addExtension(new extension($script));
    }
}
