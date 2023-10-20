(function ($, Drupal) {
  Drupal.behaviors.kennyTrainingAjax = {
    attach: function (context, settings) {
      // Створення Ajax-запиту при зміні вибору "Choose Muscle group".
      $('#edit-muscle-groups', context).once('kenny-training-ajax').on('change', function () {
        var muscleGroupId = $(this).val();

        // Виконайте Ajax-запит на сервер і передайте обраний muscleGroupId.
        $.ajax({
          url: '/training/plan/demo', // Замініть це на ваш шлях до серверного коду.
          method: 'POST',
          data: {muscle_group_id: muscleGroupId},
          success: function (data) {
            // Оновіть #options для всіх елементів вибору вправи.
            $('.exercise-select').each(function () {
              $(this).html(data.options);
            });
          }
        });
      });
    }
  };
})(jQuery, Drupal);
