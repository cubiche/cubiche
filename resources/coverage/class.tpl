<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Language" content="en" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><tpl:projectName /> : code coverage of <tpl:className /></title>
    <link rel="stylesheet" media="screen" type="text/css" href="<tpl:relativeRootUrl />screen.css" title="Screen" />
</head>
<body>
<div id="page">
    <div id="header">
        <div id="header-left">
            <h1>Code coverage of <a href="<tpl:relativeRootUrl />index.html"><tpl:projectName /></a></h1>
        </div>
        <div id="header-right">
            <div id="breadcrumb">
                <tpl:className />
            </div>
            <h2>Class code coverage </h2>
            <div class="float-badge">
                <span class="total-coverage">
                    <tpl:classCoverageUnavailable>n/a</tpl:classCoverageUnavailable><tpl:classCoverageAvailable><tpl:classCoverageValue />%</tpl:classCoverageAvailable>
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
            <h3>Methods</h3>
            <ul class="summary">
                <tpl:methods>
                    <tpl:method>
                        <li>
                            <tpl:methodCoverageUnavailable>
                                <div class="label"><a href="#<tpl:methodName />"><tpl:methodName /></a> <span>n/a</span></div>
                            </tpl:methodCoverageUnavailable>
                            <tpl:methodCoverageAvailable>
                                <div class="bar">
                                    <div class="background"></div>
                                    <div class="graph" style="width: <tpl:methodCoverageValue />%"></div>
                                    <div class="label">
                                        <span class="percent"><tpl:methodCoverageValue />%</span>
                                        <a href="#<tpl:methodName />"><tpl:methodName /></a>
                                    </div>
                                </div>
                            </tpl:methodCoverageAvailable>
                        </li>
                    </tpl:method>
                </tpl:methods>
            </ul>
            <h3>Source</h3>
            <table cellpadding="0" cellspacing="0" class="source">
                <tr><th class="number">Line</th><th>Code</th></tr>
                <tpl:sourceFile>
                    <tpl:line><tr><td class="number"><tpl:anchor><a name="<tpl:method />"></a></tpl:anchor><tpl:lineNumber /></td><td><pre><tpl:code /></pre></td></tr></tpl:line>
                    <tpl:coveredLine><tr class="covered"><td class="number"><tpl:anchor><a name="<tpl:method />"></a></tpl:anchor><tpl:lineNumber /></td><td><pre><tpl:code /></pre></td></tr></tpl:coveredLine>
                    <tpl:notCoveredLine><tr class="notCovered"><td class="number"><tpl:anchor><a name="<tpl:method />"></a></tpl:anchor><tpl:lineNumber /></td><td><pre><tpl:code /></pre></td></tr></tpl:notCoveredLine>
                </tpl:sourceFile>
            </table>
        </div>
        <div id="footer">
            <p>Code coverage report powered by <a href="http://atoum.org">atoum</a></p>
        </div>
    </div>
</div>
</body>
</html>