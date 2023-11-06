(function ($, Drupal, once) {
  Drupal.behaviors.kennyTrainingBehavior = {
    attach: function (context, settings) {
      var name = 'kennyTrainingBehavior';
      var selector = '.favorite-button:not(.processed)';
      once(name, selector, context).forEach(function (element) {
        var button = $(element);

        button.click(function (e) {
          e.preventDefault();
          var uid = button.data('uid');
          var nid = button.data('nid');

          var favoriteActionUrl = button.hasClass('add-favorite') ? '/add-to-favorite/' : '/delete-from-favorite/';
          favoriteActionUrl += uid + '/' + nid;

          $.ajax({
            type: "POST",
            url: favoriteActionUrl,
            success: function () {
              if (button.hasClass('add-favorite')) {
                button.removeClass('add-favorite').addClass('remove-favorite');
                button.text('Remove from favorite');
              } else if (button.hasClass('remove-favorite')) {
                button.removeClass('remove-favorite').addClass('add-favorite');
                button.text('Add to favorite');

                if (window.location.pathname === '/favorite-training-plan') {
                  // Видалення батьківського елемента
                  var teaserElement = button.closest('.node-training-plan-teaser');
                  if (teaserElement.length > 0) {
                    teaserElement.css('display', 'none');
                  }
                }
              }
            }
          });
        });
      });
    }
  };
})(jQuery, Drupal, once);
