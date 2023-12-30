// function followStatus() {
//   const btn = document.getElementById('follow-btn');
  
//   if (btn.innerHTML == 'Unfollow') {
//     btn.innerHTML = `Follow`;
//   } else {
//     btn.innerHTML = `Unfollow`;
//   }
// }

$(function() {
  $('.follow-btn').on('click', function() {
    const follower = $(this).data('follower');
    const followed = $(this).data('followed');
    const status = $(this).data('status');
    const follower_count = $(this).data('follower_count');
    $.ajax({
      type: "POST",
      url: 'http://localhost/nolepverse/actions/follow.php',
      data: {
        action: "follow",
        follower: follower,
        followed: followed
      },
      success: function(response) {
        console.log('')
        const btn = document.getElementById('follow-btn');
        if (status == 'Unfollow') {
          btn.innerHTML = `Follow`;
        } else {
          btn.innerHTML = `Unfollow`;
        }
      }
    });
  });
});