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
        format: 'dd/mm/yyyy', //Very important. This is used by "africassoc_utils_views_query_alter"
        autoclose: true,
        clearBtn: true,
        todayHighlight: true
      });
    }
  }
})(jQuery, Drupal);
