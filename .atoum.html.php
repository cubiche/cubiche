<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use \mageekguy\atoum;

// Code coverage setup
$coverageHtmlField = new atoum\report\fields\runner\coverage\html('Cubiche', '/var/www/coverage/web');
$coverageHtmlField->setRootUrl('http://coverage.cubiche.dev');

$script
    ->addDefaultReport()
    ->addField($coverageHtmlField)
;
