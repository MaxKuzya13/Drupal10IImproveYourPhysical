(function ($, Drupal, once) {
  Drupal.behaviors.kennyTrackerBehavior = {
    attach: function (context, settings) {

      let name = 'kennyTrackerBehavior';
      let container = '.tracker__selected-fields';
      let selector = '.block-kenny-tracker';

      once(name, selector, context).forEach(function (element) {
        // Знаходимо кількість дочірніх елементів
        let itemsCount = $(container).children().length;


        // Задаємо стилі для елементів з класом .tracker-container
        $(element).find('.tracker-container').css({
          'display': 'grid',
          'grid-template-columns': 'repeat(' + itemsCount + ', 1fr)'
        });


      });


    }
  };
})(jQuery, Drupal, once);