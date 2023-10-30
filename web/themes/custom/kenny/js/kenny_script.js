/**
 * @file
 * kenny behaviors.
 */
(function ($, Drupal) {
  Drupal.behaviors.kennyTrainingStats = {
    attach: function (context) {
      // Задайте слухача подій для кнопок "month", "3 months", "6 months" і "1 year".
      $('.filter-button', context).on('click', function (event) {
        event.preventDefault();
        var filter = $(this).data('filter');
        var body_part = 'biceps'; // Задайте частину тіла відповідно до вашого завдання.
        // Відправте запит на сервер.
        $.ajax({
          url: '/filter-training-plans/' + body_part + '/' + filter, // Замініть шлях на ваш контролер.
          method: 'GET',
          success: function (response) {
            // Оновити вміст сторінки з отриманими даними.
            // Переконайтесь, що у вас є контейнер для вмісту тренувань, який ви оновлюєте.
            $('.content-container').html(response);
          },
          error: function (xhr, status, error) {
            console.error('AJAX request failed: ' + error);
          }
        });
      });
    }
  };
})(jQuery, Drupal);
