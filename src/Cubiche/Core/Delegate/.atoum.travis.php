<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Cubiche\Tests\Report\Coverage\Coveralls;
use mageekguy\atoum\visibility\extension as Extension;

/* @var \mageekguy\atoum\configurator $script */
$script->addDefaultReport();
/* @var \mageekguy\atoum\runner $runner */
$runner->addTestsFromDirectory(__DIR__.'/Tests');
$runner->addExtension(new Extension($script));

if ($token = getenv('COVERALLS_REPO_TOKEN')) {
    $coverallsReport = new Coveralls(__DIR__, $token);

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
}
