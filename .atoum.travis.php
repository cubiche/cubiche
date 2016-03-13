<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Cubiche\Domain\Tests\Report\Coverage\Coveralls;
use mageekguy\atoum\visibility\extension;

/* @var \mageekguy\atoum\configurator $script */
$script->addDefaultReport();
if ($token = getenv('COVERALLS_REPO_TOKEN')) {
    $coverallsReport = new Coveralls(__DIR__.'/src', $token, 'src');

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

    /* @var \mageekguy\atoum\runner $runner */
    $runner->addReport($coverallsReport);
    $runner->addExtension(new extension($script));
}
