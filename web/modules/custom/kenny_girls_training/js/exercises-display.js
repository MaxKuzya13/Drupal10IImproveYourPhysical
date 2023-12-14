(function ($, Drupal, once) {
  Drupal.behaviors.kennyGirlsTrainingBehavior = {
    attach: function (context, settings) {

      let name = 'kennyGirlsTrainingBehavior';
      let selector = '.show-all-exercises-button';


      once(name, selector, context).forEach(function (element) {
        let button = $(element);
        let termIdentifier = $(element).data('term-identifier');

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
      once(name, selector_video, context).forEach(function (element) {
        let button = $(element);
        let showVideo = $(element).data('show-video');

        button.click(function (e) {

          e.preventDefault();

          // Знаходження всіх елементів .playable-video video і ставлення їх на паузу
          $('.playable-video video').each(function () {
            this.pause();
          });

          $("." + showVideo + '_video').toggleClass('hide-exercises');
          $(element).toggleClass('hide-video');

        })
      })

      let videos = '.playable-video video';
      once(name, videos, context).forEach(function (video) {

        // Додайте клас '.autoplay-video' для відстеження відео, яке слід автоматично відтворювати
        $(video).addClass('autoplay-video');
        video.muted = true;

        video.addEventListener('click', function () {
          // Переконайтеся, що відео не відтворюється
          if (video.paused) {
            video.play(); // Запустіть відео при кліку
          } else {
            video.pause(); // Пауза відео, якщо воно вже відтворюється
          }
        });

        video.addEventListener('ended', function () {
          // Переконайтеся, що відео має клас '.autoplay-video', і якщо так, зациклюйте його
          if ($(video).hasClass('autoplay-video')) {
            video.play();
          }
        });
      });

    }
  };
})(jQuery, Drupal, once);