  const sidebar = document.getElementById('sidebar').classList;
  const toggle = document.getElementById('toggle');

  toggle.addEventListener('click', function() {
    toggle.checked ? sidebar.remove('-ml-36') : sidebar.add('-ml-36')
  })