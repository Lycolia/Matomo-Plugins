<?php

namespace Piwik\Plugins\EvolutionPeriodSwitcher;

use Piwik\Common;
use Piwik\Plugin;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugin\Visualization;
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution;

class EvolutionPeriodSwitcher extends Plugin
{
    public function registerEvents()
    {
        return [
            'ViewDataTable.configure.end' => 'onConfigureEnd',
            'Visualization.beforeRender' => 'onBeforeRender',
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
        ];
    }

    public function onConfigureEnd(ViewDataTable $view)
    {
        if ($view::ID !== Evolution::ID) {
            return;
        }

        $period = Common::getRequestVar('period', '', 'string');
        if ($period !== 'range') {
            return;
        }

        $view->config->show_periods = true;
        $view->config->show_limit_control = true;
    }

    public function onBeforeRender(Visualization $view)
    {
        if ($view::ID !== Evolution::ID) {
            return;
        }

        $period = Common::getRequestVar('period', '', 'string');
        if ($period !== 'range') {
            return;
        }

        $displayPeriod = $view->requestConfig->request_parameters_to_modify['period'] ?? '';
        $rangeDate = $view->requestConfig->request_parameters_to_modify['date'] ?? Common::getRequestVar('date', '', 'string');

        if ($displayPeriod !== '') {
            $view->config->custom_parameters['evolutionPeriod'] = $displayPeriod;
            $view->config->custom_parameters['evolutionRangeDate'] = $rangeDate;
        }
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = 'plugins/EvolutionPeriodSwitcher/javascripts/evolutionPeriodSwitcher.js';
    }
}
