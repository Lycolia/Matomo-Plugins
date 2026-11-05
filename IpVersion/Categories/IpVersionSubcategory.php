<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL v3 or later
 */

namespace Piwik\Plugins\IpVersion\Categories;

use Piwik\Category\Subcategory;

class IpVersionSubcategory extends Subcategory
{
    protected $categoryId = 'General_Visitors';
    protected $id = 'IpVersion_IpVersion';
    protected $order = 40;
}
