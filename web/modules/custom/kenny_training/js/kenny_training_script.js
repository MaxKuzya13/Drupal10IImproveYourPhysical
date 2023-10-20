(function ($) {
  Drupal.behaviors.kennyTrainingForm = {
    attach: function (context, settings) {
      // Змінні для зберігання списків вправ для кожного поля.
      var exerciseOptions = {
        0: ['Вправа 1', 'Вправа 2', 'Вправа 3'], // Початковий список вправ.
      };

      // Функція для оновлення вправ в залежності від вибору в попередніх полях.
      function updateExerciseOptions(selectIndex, selectedValue) {
        var $select = $('select[name="exercises_' + selectIndex + '"]');
        $select.empty();

        // Заповніть список вправ на основі вибору користувача у попередніх полях.
        var availableExercises = exerciseOptions[selectIndex];
        for (var i = 0; i < availableExercises.length; i++) {
          if (availableExercises[i] !== selectedValue) {
            $select.append($('<option>', {
              value: availableExercises[i],
              text: availableExercises[i],
            }));
          }
        }
      }

      // Додаємо обробник події change для всіх полів вправ.
      $('select[name^="exercises_"]').once('kennyTrainingForm').on('change', function () {
        var $this = $(this);
        var selectedValue = $this.val();

        // Знайдіть індекс поточного поля.
        var currentIndex = parseInt($this.attr('name').match(/exercises_(\d+)/)[1]);

        // Оновіть наступне поле вправ.
        if (currentIndex < 5) { // Перевірка на максимальну кількість полів, на ваш вибір.
          updateExerciseOptions(currentIndex + 1, selectedValue);
        }
      });
    },
  };
})(jQuery);
