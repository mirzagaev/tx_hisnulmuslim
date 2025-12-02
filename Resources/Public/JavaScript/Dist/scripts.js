// beim Laden
if (localStorage.getItem('theme') === 'dark') {
  document.documentElement.classList.add('dark');
}

// beim Umschalten
function toggleDarkMode() {
  document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme',
    document.documentElement.classList.contains('dark') ? 'dark' : 'light'
  );
}

$(document).ready(function() {
    $("#search_open").click(function() {
        $("#pageheadline").hide();
        $("#mobilemenu_opener").hide();
        $("#search_close").show();
        $("#searchfield").show();
        $("#searchfield").focus();
    });
    $("#search_close").click(function() {
        $("#pageheadline").show();
        $("#mobilemenu_opener").show();
        $("#search_close").hide();
        $("#searchfield").hide();
    });

    $(".chapterLinks").on("click", function() {
      $("#cart-drawer").removeClass("translate-x-full");
      $("#overlay").removeClass("hidden");
      return false;
    });

    $("#overlay, #closeDrawer").on("click", function() {
      $("#cart-drawer").addClass("translate-x-full");
      $("#overlay").addClass("hidden");
      return false;
    });
});