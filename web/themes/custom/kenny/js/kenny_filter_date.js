(function ($, Drupal, once) {
  Drupal.behaviors.kennyFilterDateBehavior = {
    attach: function (context, settings) {
      var name = 'kennyFilterDateBehavior';
      var selector = '.date_item:not(.processed)';
      once(name, selector, context).forEach(function (element){
        var item = $(element);

        item.click(function (e) {
          e.preventDefault();

          var filterValue = item.data('filter');
          var url = item.attr('href');

          $.ajax({
            url: url,
            method: 'GET',
            data: { field_training_date_value: filterValue },
            success: function (data) {
              var newContent = $(data).find('.main-layout__inner');
              $('.main-layout__inner').html(newContent);
            },
            error: function (xhr, status, error) {
              console.error(error);
            },
          });
        });
      });
    }
  };
})(jQuery, Drupal, once);
