<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpVersion;

use Piwik\Archive;
use Piwik\DataTable;
use Piwik\Piwik;
use Piwik\Plugins\IpVersion\RecordBuilders\IpVersion as IpVersionRecordBuilder;

/**
 * The IpVersion API lets you access visit metrics grouped by IP version (IPv4 or IPv6).
 *
 * @method static \Piwik\Plugins\IpVersion\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    /**
     * Returns visit metrics grouped by IP version.
     *
     * @param int|string|int[] $idSite Website ID(s) to query.
     * @param 'day'|'week'|'month'|'year'|'range' $period The period to process.
     * @param string $date The date or date range to process.
     * @param string|null|false $segment Custom segment to filter the report.
     * @return DataTable|DataTable\Map Visit metrics grouped by IP version.
     */
    public function getIpVersion($idSite, string $period, string $date, $segment = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $archive = Archive::build($idSite, $period, $date, $segment);
        $dataTable = $archive->getDataTable(IpVersionRecordBuilder::RECORD_NAME);
        $dataTable->queueFilter('ReplaceColumnNames');
        $dataTable->queueFilter('ReplaceSummaryRowLabel');
        $dataTable->filter('AddSegmentValue');

        return $dataTable;
    }
}
