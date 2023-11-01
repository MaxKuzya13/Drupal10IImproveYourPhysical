(function ($) {
  $(document).ready(function () {
    $('.filter-button').on('click', function () {
      var selectedDate = $(this).data('period');
      console.log(selectedDate);
      // Викликати Ajax контролер і передати значення selectedPeriod.
      $.ajax({
        url: '/change-filter-date/' + selectedDate,
        success: function (data) {
          updatePage(data);
        }
      });
    });
  });
})(jQuery);