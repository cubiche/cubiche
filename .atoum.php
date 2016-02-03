<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$report = $script->addDefaultReport();

$runner->addTestsFromDirectory(__DIR__.'/src');
$runner->addExtension(new mageekguy\atoum\visibility\extension($script));
