(function ($) {
  $(document).ready(function () {
    $('.favorite-button').click(function (e) {
      e.preventDefault();

      var button = $(this);
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
          }
        }
      });
    });


  });
})(jQuery);


