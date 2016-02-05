<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Code coverage of <tpl:projectName /></title>
    <link rel="stylesheet" media="screen" type="text/css" href="screen.css" title="Screen" />
</head>
<body>
<div id="page">
    <div id="header">
        <div id="header-left">
            <h1>Code coverage of <tpl:projectName /></h1>
        </div>
        <div id="header-right">
            <h2>Global coverage</h2>
            <div class="float-badge">
                <span class="total-coverage">
                    <tpl:coverageUnavailable>n/a</tpl:coverageUnavailable>
                    <tpl:coverageAvailable><tpl:coverageValue />%</tpl:coverageAvailable>
                </span>
            </div>
        </div>
    </div>
    <div id="sidebar">
        <ul class="components">
            <li class="header">Components</li>
            <li><span class="percent">100 %</span><a href="#">Cras justo odio</a></li>
            <li><span class="percent">100 %</span><a href="#">Dapibus ac facilisis in</a></li>
            <li><span class="percent">100 %</span><a href="#">Morbi leo risus</a></li>
            <li><span class="percent">100 %</span><a href="#">Porta ac consectetur ac</a></li>
            <li><span class="percent">100 %</span><a href="#">Vestibulum at eros</a></li>
        </ul>
    </div>
    <div id="content-wrapper">
        <div id="content">
            <ul class="summary">
                <tpl:classCoverage>
                    <li>
                        <tpl:classCoverageUnavailable>
                            <div class="bar">
                                <div class="label"><tpl:className /> <span>n/a</span></div>
                            </div>
                        </tpl:classCoverageUnavailable>
                        <tpl:classCoverageAvailable>
                            <div class="bar">
                                <div class="background"></div>
                                <div class="graph" style="width: <tpl:classCoverageValue />%"></div>
                                <div class="label">
                                    <span class="percent"><tpl:classCoverageValue />%</span>
                                    <a href="<tpl:classUrl />"><tpl:className /></a>
                                </div>
                            </div>
                        </tpl:classCoverageAvailable>
                    </li>
                </tpl:classCoverage>
            </ul>
            <div id="footer">
                <p>Code coverage report powered by <a href="http://atoum.org">atoum</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>