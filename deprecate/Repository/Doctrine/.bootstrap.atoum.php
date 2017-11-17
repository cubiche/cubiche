<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/vendor/atoum/atoum/scripts/runner.php';

if (\file_exists(__DIR__.'/.atoum.config.php')) {
    require_once __DIR__.'/.atoum.config.php';
} else {
    require_once __DIR__.'/.atoum.config.dist';
}
