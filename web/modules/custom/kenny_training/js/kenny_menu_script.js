(function ($, Drupal, once) {
  Drupal.behaviors.kennyMenuBehavior = {
    attach: function (context, settings) {
      var name = 'kennyMenuBehavior';
      var selector = '.mobile_menu-button:not(.processed)';
      once(name, selector, context).forEach(function (element){
        var button = $(element);
        button.click(function (e){
          var mobileMenu = button.parent().find('.mobile_menu');

          if (mobileMenu.css('display') === 'none') {
            mobileMenu.css('display', 'flex');
          } else {
            mobileMenu.css('display', 'none');
          }

        })

        if ($(window).width() <= 800) {
          var mobileMenu = $(element).parent().find('.mobile_menu');
          mobileMenu.css('display', 'none');
        }

      })

    }
  };
})(jQuery, Drupal, once);
