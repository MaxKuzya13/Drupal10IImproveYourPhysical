(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlsTrainingBehavior = {
    attach: function (context, settings) {
      console.log('WOW');
      let name = 'kennyGirlsTrainingBehavior';
      let selector = '.show-all-exercises-button';

      for (let i = 3; i < 10; i++) {
        let hideInnerThigh = '.exercise-container-inner-thigh_' + i;
        let hideQuadriceps = '.exercise-container-quadriceps_' + i;

        let hideHamstring = '.exercise-container-hamstring_' + i;
        let hideShoulders = '.exercise-container-shoulders_' + i;
        let hideTriceps = '.exercise-container-triceps_' + i;
        let hideBiceps = '.exercise-container-biceps_' + i;
        let hideBack = '.exercise-container-back_' + i;
        let hideChest = '.exercise-container-chest_' + i;
        let hideGlutes = '.exercise-container-glutes-container_' + i;
        $(hideInnerThigh).hide();
        $(hideQuadriceps).hide();
        $(hideHamstring).hide();
        $(hideShoulders).hide();
        $(hideTriceps).hide();
        $(hideBiceps).hide();
        $(hideBack).hide();
        $(hideChest).hide();
        $(hideGlutes).hide();
      }
      once(name, selector, context).forEach(function (element){
        let button = $(element);
        let termIdentifier = $(element).data('term-identifier');

        button.click(function (e) {
          e.preventDefault();
          for (let i = 3; i < 10; i++) {
            $("." + termIdentifier + '_' + i).show();
          }
          $(element).hide();

        })
      })

    }
  };
})(jQuery, Drupal, once);