/**
 * EvolutionPeriodSwitcher - Override period selector behavior for range periods.
 *
 * When period=range, the default handlePeriod() rewrites self.param['period']
 * to the clicked period (e.g. 'day'), losing the original range context.
 * This script replaces that behavior: it keeps period=range, preserves the
 * original date range, and adds force_evolution_period for the server-side
 * OverridableEvolutionPeriodSelector to pick up.
 */
(function ($, require) {
    var exports = require('piwik/UI');
    var EvolutionProto = exports.JqplotEvolutionGraphDataTable.prototype;
    var originalPostBind = EvolutionProto.postBindEventsAndApplyStyleHook;

    EvolutionProto.postBindEventsAndApplyStyleHook = function (domElem) {
        if (originalPostBind) {
            originalPostBind.call(this, domElem);
        }

        var self = this;
        var evolutionPeriod = self.param.evolutionPeriod;

        // Only act when we're in range mode with an evolution period set by the plugin
        if (!evolutionPeriod) {
            return;
        }

        // Update the period dropdown label and active icon to reflect the actual display period
        var piwikPeriods = window.CoreHome.Periods;
        var displayPeriodObj = piwikPeriods.get(evolutionPeriod);
        if (displayPeriodObj) {
            $('.periodName', domElem).html(displayPeriodObj.getDisplayText());
        }

        // Update active icon highlighting
        $('.dataTablePeriods .tableIcon', domElem).each(function () {
            var $el = $(this);
            if ($el.attr('data-period') === evolutionPeriod) {
                $el.addClass('activeIcon');
            } else {
                $el.removeClass('activeIcon');
            }
        });

        // Replace the click handler that handlePeriod() already bound
        var $periodSelect = $('.dataTablePeriods .tableIcon', domElem);
        $periodSelect.off('click');
        $periodSelect.on('click', function () {
            var newPeriod = $(this).attr('data-period');
            if (!newPeriod || newPeriod === self.param.evolutionPeriod) {
                return;
            }

            // Update UI label
            var periodObj = piwikPeriods.get(newPeriod);
            if (periodObj) {
                $('.periodName', domElem).html(periodObj.getDisplayText());
            }

            // Update active icon
            $periodSelect.each(function () {
                var $el = $(this);
                if ($el.attr('data-period') === newPeriod) {
                    $el.addClass('activeIcon');
                } else {
                    $el.removeClass('activeIcon');
                }
            });

            // Keep period=range and the original date, just add force_evolution_period
            self.param['force_evolution_period'] = newPeriod;
            self.param['evolutionPeriod'] = newPeriod;
            self.reloadAjaxDataTable();
        });
    };
})(jQuery, require);
