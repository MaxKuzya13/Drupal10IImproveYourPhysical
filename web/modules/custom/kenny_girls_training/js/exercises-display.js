(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlsTrainingBehavior = {
    attach: function (context, settings) {

      let name = 'kennyGirlsTrainingBehavior';
      let selector = '.show-all-exercises-button';


      once(name, selector, context).forEach(function (element){
        let button = $(element);
        let termIdentifier = $(element).data('term-identifier');
        console.log(termIdentifier);

        button.click(function (e) {
          e.preventDefault();
          for (let i = 0; i < 10; i++) {
            $("." + termIdentifier + '_' + i).show();
            // .exercise-container-shoulders_0
          }
          if(button.hasClass('show-all-exercises-button')) {
            for (let i = 2; i < 10; i++) {
              $("." + termIdentifier + '_' + i).removeClass('hide-exercises');
            }
            button.removeClass('show-all-exercises-button');
            button.addClass('hide-all-exercises-button');
            button.val('Hide exercises');
          } else {
            for (let i = 3; i < 10; i++) {
              $("." + termIdentifier + '_' + i).addClass('hide-exercises');
            }
            button.removeClass('hide-all-exercises-button');
            button.addClass('show-all-exercises-button');
            button.val('Show all exercises');
          }


        })
      })

      let selector_video = '.show-video';
      once(name, selector_video, context).forEach(function (element){
        let button = $(element);
        let showVideo = $(element).data('show-video');

        button.click(function (e) {

          e.preventDefault();
          console.log(showVideo);
          $("." + showVideo + '_video').toggleClass('hide-exercises');
          $(element).toggleClass('hide-video');

        })
      })

    }
  };
})(jQuery, Drupal, once);