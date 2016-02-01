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

$report = $script->addDefaultReport();

// LOGO

// This will add the atoum logo before each run.
$report->addField(new atoum\report\fields\runner\atoum\logo());

// This will add a green or red logo after each run depending on its status.
$report->addField(new atoum\report\fields\runner\result\logo());

//CODE COVERAGE SETUP

// Please replace in next line "Project Name" by your project name and "/path/to/destination/directory"
// by your destination directory path for html files.
$coverageField = new atoum\report\fields\runner\coverage\html('Cubiche', '/coverage');

// Please replace in next line http://url/of/web/site by the root url of your code coverage web site.
$coverageField->setRootUrl('http://url/of/web/site');

$report->addField($coverageField);

$script->noCodeCoverageForNamespaces('mageekguy');
$script->bootstrapFile('.bootstrap.atoum.php');

$runner->addTestsFromDirectory(__DIR__.'/src');
$runner->addExtension(new mageekguy\atoum\visibility\extension($script));
