<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 */

namespace Piwik\Plugins\IpVersion\RecordBuilders;

use Piwik\ArchiveProcessor;
use Piwik\ArchiveProcessor\Record;
use Piwik\ArchiveProcessor\RecordBuilder;
use Piwik\DataTable;
use Piwik\Metrics;
use Piwik\Plugins\IpVersion\Columns\IpVersion as IpVersionDimension;

class IpVersion extends RecordBuilder
{
    public const RECORD_NAME = 'IpVersion_versions';

    public function __construct()
    {
        parent::__construct();
        $this->columnToSortByBeforeTruncation = Metrics::INDEX_NB_VISITS;
    }

    public function getRecordMetadata(ArchiveProcessor $archiveProcessor): array
    {
        return [Record::make(Record::TYPE_BLOB, self::RECORD_NAME)];
    }

    protected function aggregate(ArchiveProcessor $archiveProcessor): array
    {
        $record = new DataTable();

        $cursor = $archiveProcessor->getLogAggregator()->queryVisitsByDimension([
            'label' => IpVersionDimension::SQL_EXPRESSION,
        ]);

        while ($row = $cursor->fetch()) {
            $record->sumRowWithLabel($row['label'], [
                Metrics::INDEX_NB_UNIQ_VISITORS => $row[Metrics::INDEX_NB_UNIQ_VISITORS],
                Metrics::INDEX_NB_VISITS => $row[Metrics::INDEX_NB_VISITS],
                Metrics::INDEX_NB_ACTIONS => $row[Metrics::INDEX_NB_ACTIONS],
                Metrics::INDEX_NB_USERS => $row[Metrics::INDEX_NB_USERS],
                Metrics::INDEX_MAX_ACTIONS => $row[Metrics::INDEX_MAX_ACTIONS],
                Metrics::INDEX_SUM_VISIT_LENGTH => $row[Metrics::INDEX_SUM_VISIT_LENGTH],
                Metrics::INDEX_BOUNCE_COUNT => $row[Metrics::INDEX_BOUNCE_COUNT],
                Metrics::INDEX_NB_VISITS_CONVERTED => $row[Metrics::INDEX_NB_VISITS_CONVERTED],
            ]);
        }

        return [self::RECORD_NAME => $record];
    }
}
