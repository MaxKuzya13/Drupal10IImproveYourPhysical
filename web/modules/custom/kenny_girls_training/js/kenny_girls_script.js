(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlBehavior = {
    attach: function (context, settings) {
      // Початкова кількість полів для відображення.
      var count = 3;

      // Приховати всі поля, крім перших трьох.
      for (var i = 0; i < 10; i++) {
        var exercise = $('#exercises_' + (i), context);
        var weight = $('.form-item-weight-' + (i), context);
        var repetition = $('.form-item-repetition-' + (i), context);
        var approaches = $('.form-item-approaches-' + (i), context);
        if (i < count) {
          exercise.show();
          weight.show();
          repetition.show();
          approaches.show();
        } else {
          exercise.hide();
          weight.hide();
          repetition.hide();
          approaches.hide();
        }
      }

      var name = 'kennyGirlBehavior';
      var selector = '#add-field';
      once(name, selector, context).forEach(function (element) {
        var button = $(element);

        button.click(function (e) {
          e.preventDefault();
          count++;

          for (var k = 0; k < 10; k++) {
            var exercise = $('#exercises_' + (k), context);
            var weight = $('.form-item-weight-' + (k), context);
            var repetition = $('.form-item-repetition-' + (k), context);
            var approaches = $('.form-item-approaches-' + (k), context);
            if (k < count) {
              exercise.show();
              weight.show();
              repetition.show();
              approaches.show();
            } else {
              exercise.hide();
              weight.hide();
              repetition.hide();
              approaches.hide();
            }
          }
        })
      })



    }
  };
})(jQuery, Drupal, once);
