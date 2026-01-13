  $(document).ready(function () {
    const $menuButton = $(".prox-menu-button");
    const $menu = $(".prox-menu-surface");

    // Toggle menu
    $menuButton.on("click", function (e) {
      e.stopPropagation();
      $menu.toggleClass("open").focus();

      // Accessibility
      $(this).attr("aria-expanded", $menu.hasClass("open"));
    });

    // Prevent closing when clicking inside menu
    $menu.on("click", function (e) {
      e.stopPropagation();
    });

    // Close when clicking outside
    $(document).on("click", function () {
      $menu.removeClass("open");
      $menuButton.attr("aria-expanded", "false");
    });

    // Close on ESC key
    $(document).on("keydown", function (e) {
      if (e.key === "Escape") {
        $menu.removeClass("open");
        $menuButton.attr("aria-expanded", "false");
      }
    });
  });