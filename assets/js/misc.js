(function ($) {
  'use strict';
  $(function () {
    var sidebar = $('.prox-drawer-menu');
    var body = $('body');

    if($('.prox-drawer').length) {
      var drawer = mdc.drawer.MDCDrawer.attachTo(document.querySelector('.prox-drawer'));
      // toggler icon click function
      document.querySelector('.sidebar-toggler').addEventListener('click', function () {
        drawer.open = !drawer.open;
      });
    }

    // Initially collapsed drawer in below desktop
    if(window.matchMedia('(max-width: 991px)').matches) {
      if(document.querySelector('.prox-drawer.prox-drawer--dismissible').classList.contains('prox-drawer--open')) {
        document.querySelector('.prox-drawer.prox-drawer--dismissible').classList.remove('prox-drawer--open'); 
      }
    }

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required
    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
    $('.prox-drawer-item .prox-drawer-link', sidebar).each(function () {
      var $this = $(this);
      if (current === "") {
        //for root url
        if ($this.attr('href').indexOf("index.html") !== -1) {
          $(this).addClass('active');
          if ($(this).parents('.prox-expansion-panel').length) {
            $(this).closest('.prox-expansion-panel').addClass('expanded');
          }
        }
      } else {
        //for other url
        if ($this.attr('href').indexOf(current) !== -1) {
          $(this).addClass('active');
          if ($(this).parents('.prox-expansion-panel').length) {
            $(this).closest('.prox-expansion-panel').addClass('expanded'); 
            $(this).closest('.prox-expansion-panel').show();
          }
        }
      }
    });

    // Toggle Sidebar items
    $('[data-toggle="expansionPanel"]').on('click', function () {
      // close other items
      $('.prox-expansion-panel').not($('#' + $(this).attr("data-target"))).hide(300);
      $('.prox-expansion-panel').not($('#' + $(this).attr("data-target"))).prev('[data-toggle="expansionPanel"]').removeClass("expanded");
      // Open toggle menu
      $('#' + $(this).attr("data-target")).slideToggle(300, function() {
        $('#' + $(this).attr("data-target")).toggleClass('expanded');
      });
    });


    // Add expanded class to prox-drawer-link after expanded
    $('.prox-drawer-item .prox-expansion-panel').each(function () {
      $(this).prev('[data-toggle="expansionPanel"]').on('click', function () {
        $(this).toggleClass('expanded');
      })
    });

    //Applying perfect scrollbar to sidebar
    if (!body.hasClass("rtl")) {
      if ($('.prox-drawer .prox-drawer__content').length) {
        const chatsScroll = new PerfectScrollbar('.prox-drawer .prox-drawer__content');
      }
    }

  });
})(jQuery);