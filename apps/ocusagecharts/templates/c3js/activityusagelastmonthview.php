<?php
use OCA\ocUsageCharts\Entity\ChartConfig;

$label = $l->t('activities');

/** @var ChartConfig $chartConfig */
$chartConfig = $_['chart']->getConfig();

$url =\OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $chartConfig->getId(), 'requesttoken' => $_['requesttoken']));
echo '
<h1>';
p($l->t($chartConfig->getChartType()));
echo '</h1>
<div class="chart defaultChart" id="chart" data-url="' . $url . '" data-type="line" data-shortlabel="" data-format="%Y-%m-%d" data-label="' . $label . '"><div class="icon-loading" style="height: 60px;"></div></div>';
