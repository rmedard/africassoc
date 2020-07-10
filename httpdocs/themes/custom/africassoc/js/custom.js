/**
 * @file
 * Custom utilities.
 *
 */
(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.africassoc = {
    attach: function (context, settings) {
      $('input#edit-field-event-date-value').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        clearBtn: true,
        todayHighlight: true
      });
    }
  }
})(jQuery, Drupal);
