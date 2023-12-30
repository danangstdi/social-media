$(function() {
  $("[id^='like']").on('click', function() {
    const story_id = $(this).data('story_id');
    const username = $(this).data('username');
    $.ajax({
      type: "POST",
      url: 'http://localhost/nolepverse/actions/like.php',
      data: {
        action: "like",
        story_id: story_id,
        username: username
      },
      success: function(response) {
        
      }
    });
  });
});