$(document).ready(function () {
  // Sidebar toggler
  $(".toggler").click(function () {
    $(".sidebar").toggleClass('sidebar-right-show');
  });

  // tooltip activator
  $('[data-toggle="tooltip"]').tooltip();

  // datepicker
  $('.datepicker').datepicker();

});