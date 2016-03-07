<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>Code coverage of <tpl:projectName/></title>

    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css">
    <link rel="stylesheet" media="screen" type="text/css" href="<tpl:relativeRootUrl />screen.css" title="Screen" />
</head>
<body>

<div class="ui fixed main menu">
    <div class="ui fluid container">
        <div class="ui two column grid">
            <div class="three wide column">
                <a href="#" class="header item">
                    <img class="logo" src="<tpl:relativeRootUrl />logo.png">
                    <tpl:projectName/>
                </a>
            </div>
            <div class="thirteen wide column">
                <div class="ui two column grid">
                    <div class="twelve wide column">
                        <div class="ui basic segment">
                            <tpl:pathTemplate>
                                <div class="ui breadcrumb">
                                    <tpl:pathItem>
                                        <a href="<tpl:pathItemUrl />" class="section"><tpl:pathItemName/></a>
                                        <span class="divider">/</span>
                                    </tpl:pathItem>
                                    <tpl:pathItemLast>
                                        <div class="active section"><tpl:pathItemName/></div>
                                    </tpl:pathItemLast>
                                </div>
                            </tpl:pathTemplate>
                        </div>
                    </div>
                    <div class="four wide column right aligned">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="wrapper">
    <div class="ui visible left sidebar">
        <div class="ui vertical menu">
            <div class="item active">
                <h3>Project Stats</h3>
            </div>
            <div class="item">
                <tpl:coverageAvailable>
                    <div class="repoStats" data-colors='["#5CB85C", "#ff5b57"]' data-stats='[{ "value": <tpl:coverageValue />, "label": "Covered" }, { "value": <tpl:uncoverageValue />, "label": "Not covered" }]'></div>
                </tpl:coverageAvailable>
                <tpl:coverageUnavailable>
                    <div class="repoStats" data-colors='["#ff5b57"]'  data-stats='[{ "value": 100, "label": "Not covered" }]'></div>
                </tpl:coverageUnavailable>

                <div class="runStatsRelevant">
                    <strong><tpl:coveredLines/></strong>
                    of
                    <strong><tpl:relevantLines/></strong>
                    <br>
                    relevant lines covered
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <tpl:itemCoverageAvailable>
        <div class="ui sticky stats">
            <div class="ui inverted segment summary">
                <div class="ui inverted horizontal list">
                    <div class="item">
                        <div class="ui small success inverted horizontal statistic">
                            <div class="ui progress success" data-percent="<tpl:itemCoverageValue />">
                                <div class="bar" style="width: <tpl:itemCoverageValue />%;">
                                    <div class="progress"></div>
                                </div>
                            </div>
                            <div class="value">
                                <tpl:itemCoverageValue />%
                            </div>
                            <div class="label">
                                Directory
                                <br>
                                Coverage
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui small grey inverted horizontal statistic">
                            <div class="value">
                                <tpl:itemTotalLines/>
                            </div>
                            <div class="label">
                                Total
                                <br>
                                Lines
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui small grey inverted horizontal statistic">
                            <div class="value">
                                <tpl:itemRelevantLines/>
                            </div>
                            <div class="label">
                                Relevant
                                <br>
                                Lines
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ui small grey inverted horizontal statistic">
                            <div class="value">
                                <tpl:itemCoveredLines/>
                            </div>
                            <div class="label">
                                Covered
                                <br>
                                Lines
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </tpl:itemCoverageAvailable>

        <div class="ui basic very padded segment">
            <tpl:items>
                <table class="ui table items">
                    <thead>
                    <tr>
                        <th class="one wide left aligned">Coverage</th>
                        <th class="six wide left aligned">Name</th>
                        <th class="three wide left aligned">Type</th>
                        <th class="two wide right aligned">Lines</th>
                        <th class="two wide right aligned">Relevant</th>
                        <th class="two wide right aligned">Covered</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tpl:item>
                        <tr>
                            <td class="left aligned">
                                <div class="ui <tpl:itemCoverageRounded /> label">
                                    <tpl:itemCoverage /> %
                                </div>
                            </td>
                            <td class="left aligned">
                                <a href="<tpl:itemUrl />"><tpl:itemName /></a>
                            </td>
                            <td class="left aligned"><tpl:itemType /></td>
                            <td class="right aligned"><tpl:itemTotalLines /></td>
                            <td class="right aligned"><tpl:itemRelevantLines /></td>
                            <td class="right aligned"><tpl:itemCoveredLines /></td>
                        </tr>
                    </tpl:item>
                    </tbody>
                </table>
            </tpl:items>
        </div>
    </div>
</div>
<script src="<tpl:relativeRootUrl />application.js"></script>
</body>
</html>
