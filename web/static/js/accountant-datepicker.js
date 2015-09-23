/**
 * Version of Bootstrap-styled datepicker plugin with the custom options
 */
(function($) {
    $.fn.extend({
        accountantDatePicker: function () {
            $(this)
                .datepicker({ format: 'dd-mm-yyyy', weekStart: 1 })
                .on('changeDate', function () {
                    $(this).datepicker('hide');
                });

            return this;
        }
    });
})(jQuery);