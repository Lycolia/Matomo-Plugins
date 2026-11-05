<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpVersion\Columns;

use Piwik\Columns\DimensionSegmentFactory;
use Piwik\Plugin\Dimension\VisitDimension;
use Piwik\Plugin\Segment;
use Piwik\Segment\SegmentsList;

/**
 * Derives the IP version from the existing log_visit.location_ip column, so no column is added.
 */
class IpVersion extends VisitDimension
{
    /**
     * IPv4 is stored as 4 bytes. IPv4-mapped IPv6 addresses are stored as 16 bytes, and their
     * ::ffff: prefix survives IP anonymisation. IPv6::isMappedIPv4() is not equivalent here,
     * because it also matches addresses whose first 12 bytes are all zero, such as ::1.
     */
    public const SQL_EXPRESSION = "CASE WHEN LENGTH(log_visit.location_ip) = 4"
        . " OR LEFT(log_visit.location_ip, 12) = UNHEX('00000000000000000000FFFF')"
        . " THEN 'IPv4' ELSE 'IPv6' END";

    protected $category = 'General_Visitors';
    protected $type = self::TYPE_TEXT;
    protected $nameSingular = 'IpVersion_IpVersion';
    protected $columnName = 'location_ip';
    protected $sqlSegment = self::SQL_EXPRESSION;
    protected $segmentName = 'ipVersion';
    protected $acceptValues = 'IPv4, IPv6';

    public function configureSegments(SegmentsList $segmentsList, DimensionSegmentFactory $dimensionSegmentFactory)
    {
        $segment = new Segment();
        $segment->setSuggestedValuesCallback(function () {
            return ['IPv4', 'IPv6'];
        });
        $segmentsList->addSegment($dimensionSegmentFactory->createSegment($segment));
    }
}
