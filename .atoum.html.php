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
use Cubiche\Domain\Tests\Atoum\Report\Coverage\Html;

// Code coverage setup
//$coverageHtmlField = new atoum\report\fields\runner\coverage\html('Cubiche', '/var/www/coverage');
$coverageHtmlField = new Html('Cubiche', '/var/www/coverage');
$coverageHtmlField->setRootUrl('http://coverage.cubiche.dev');
$coverageHtmlField->setTemplatesDirectory(__DIR__.'/resources/coverage');

$script
    ->addDefaultReport()
    ->addField($coverageHtmlField)
;
