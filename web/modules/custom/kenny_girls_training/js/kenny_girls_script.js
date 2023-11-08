(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlTrainingFormBehavior = {
    attach: function (context, settings) {
      var name = 'kennyGirlTrainingFormBehavior';
      var selector = '.num-groups-select';
      console.log('HELLO');
      once(name, selector, context).forEach(function (element){
        var button = $(element);

        button.change(function (e){
          e.preventDefault();
          var selectedValue = $(element).val();
          // Визначаємо, чи потрібно приховати поле "Choose Muscle group".
          if (selectedValue === 'full_body') {
            $('.muscle-groups-wrapper').hide(); // Приховуємо поле.
          } else {
            $('.muscle-groups-wrapper').show(); // Показуємо поле.
          }
        })
      })
      // Обробляємо зміну вибору у полі "Number of Muscle Groups".

    }
  };
})(jQuery, Drupal, once);
