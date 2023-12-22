(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlBehavior = {
    attach: function (context, settings) {
      $('input').on('focus', function () {
        $(this).parent().next('.error').text('');
      });
      $('select').on('focus', function () {
        $(this).parent().next('.error').text('');
      });


    }
  };
})(jQuery, Drupal, once);
