<?php

namespace Piwik\Plugins\EvolutionPeriodSwitcher;

use Piwik\Common;
use Piwik\Period;
use Piwik\Plugins\CoreVisualizations\Visualizations\EvolutionPeriodSelector;

class OverridableEvolutionPeriodSelector extends EvolutionPeriodSelector
{
    private const ALLOWED_PERIODS = ['day', 'week', 'month', 'year'];

    public function getHighestPeriodInCommon(Period $originalPeriod, $comparisonPeriods): string
    {
        $forcedPeriod = Common::getRequestVar('force_evolution_period', '', 'string');

        if ($forcedPeriod !== '' && in_array($forcedPeriod, self::ALLOWED_PERIODS, true)) {
            return $forcedPeriod;
        }

        return parent::getHighestPeriodInCommon($originalPeriod, $comparisonPeriods);
    }
}
