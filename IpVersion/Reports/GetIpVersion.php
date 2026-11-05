<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpVersion\Reports;

use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution;
use Piwik\Plugins\IpVersion\Columns\IpVersion;
use Piwik\Report\ReportWidgetFactory;
use Piwik\Widget\WidgetsList;

class GetIpVersion extends Report
{
    protected function init()
    {
        parent::init();
        $this->categoryId    = 'General_Visitors';
        $this->subcategoryId = 'IpVersion_IpVersion';
        $this->dimension     = new IpVersion();
        $this->name          = Piwik::translate('IpVersion_IpVersion');
        $this->documentation = Piwik::translate('IpVersion_getIpVersionDocumentation');
        $this->order         = 1;
    }

    public function configureWidgets(WidgetsList $widgetsList, ReportWidgetFactory $factory)
    {
        parent::configureWidgets($widgetsList, $factory);

        $widgetsList->addWidgetConfig(
            $factory->createWidget()
                ->setName('General_EvolutionOverPeriod')
                ->setSubcategoryId('IpVersion_IpVersion')
                ->setAction('getEvolutionGraph')
                ->setOrder(0)
                ->forceViewDataTable(Evolution::ID)
                ->addParameters(['columns' => ['nb_visits']])
        );
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_flatten_table = false;
        $view->config->show_flatten_table_export = false;
        $view->config->show_search = false;
        $view->config->show_exclude_low_population = false;
        $view->config->columns_to_display = ['label', 'nb_visits'];

        $view->requestConfig->filter_sort_column = 'nb_visits';
        $view->requestConfig->filter_sort_order  = 'desc';
    }
}
