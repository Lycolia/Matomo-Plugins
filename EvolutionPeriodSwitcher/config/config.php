<?php

use Piwik\Plugins\CoreVisualizations\Visualizations\EvolutionPeriodSelector;
use Piwik\Plugins\EvolutionPeriodSwitcher\OverridableEvolutionPeriodSelector;

return [
    EvolutionPeriodSelector::class => Piwik\DI::autowire(OverridableEvolutionPeriodSelector::class),
];
