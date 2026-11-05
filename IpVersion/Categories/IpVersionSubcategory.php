<?php

/**
 * IpVersion - Plugin for Matomo
 *
 * @copyright Lycolia Rizzim
 * @link      https://lycolia.info
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\IpVersion\Categories;

use Piwik\Category\Subcategory;

class IpVersionSubcategory extends Subcategory
{
    protected $categoryId = 'General_Visitors';
    protected $id = 'IpVersion_IpVersion';
    protected $order = 40;
}
