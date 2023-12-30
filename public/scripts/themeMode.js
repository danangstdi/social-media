$(function() {
  $('.themeToggler').on('click', function() {
    const theme = $(this).data('theme');
    $.ajax({
      type: "POST",
      url: 'http://localhost/nolepverse/actions/theme.php',
      data: {
        action: "switchTheme",
        theme: theme
      },
      success: function(response) {
        if (document.querySelector('html').classList.contains('dark')) {
          document.querySelector('html').classList.remove('dark');
          document.querySelector('html').classList.add('light');
          document.getElementById('text-mode').innerHTML = 'Tema Terang';
      } else {
        document.querySelector('html').classList.remove('light');
        document.querySelector('html').classList.add('dark');
        document.getElementById('text-mode').innerHTML = 'Tema Gelap';
      }
      }
    });
  });
});