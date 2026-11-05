<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpVersion;

use Piwik\Common;
use Piwik\Piwik;
use Piwik\SettingsPiwik;

class Controller extends \Piwik\Plugin\Controller
{
    public function getEvolutionGraph()
    {
        $view = $this->getLastUnitGraph($this->pluginName, __FUNCTION__, 'IpVersion.getIpVersion');

        $columns = Common::getRequestVar('columns', false);
        $view->config->columns_to_display = false !== $columns
            ? Piwik::getArrayFromApiParameter($columns)
            : ['nb_visits'];

        $selectableColumns = ['nb_visits', 'nb_actions'];
        if (SettingsPiwik::isUniqueVisitorsEnabled(Common::getRequestVar('period', false))) {
            $selectableColumns[] = 'nb_uniq_visitors';
        }
        $view->config->selectable_columns = $selectableColumns;

        $rows = Common::getRequestVar('rows', false);
        $view->config->row_picker_match_rows_by = 'label';
        $view->config->rows_to_display = false !== $rows
            ? array_map('urldecode', Piwik::getArrayFromApiParameter($rows))
            : ['IPv4', 'IPv6'];

        return $this->renderView($view);
    }
}
