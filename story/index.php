<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Comment.php';
require_once '../apps/database/Mix.php';

if (!isset($_SESSION['login'])) {
  header('Location: ' . BASE_URL . '/error/unlogged');
}

$db = new Database();
$comment = new Comment($db->getConnection());
$mix = new Mix($db->getConnection());
$s = $_GET['s'];
$username = $_SESSION['username'];
$story = $mix->query("SELECT * FROM story JOIN users ON story.username = users.username WHERE story_id = '$s'")[0];
$userTheme = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];
$getComment = $mix->query("SELECT * FROM comment 
                                          JOIN story ON comment.story_id = story.story_id 
                                          JOIN users ON comment.username = users.username 
                                          WHERE comment.story_id = '$s' ORDER BY comment.comment_at DESC");

if (isset($_POST['btn-comment'])) {
  $comment->createComment($_POST);
}

if (isset($_POST['comment-delete'])) {
  if ($comment->deleteComment($_POST) > 0) {
    header('Location:' . BASE_URL . '/story/?u=' . $_SESSION['username'] . '&s=' . $s);
  }
}

?>

<html lang="en" id="theme-switch" class="<?= $userTheme['theme'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?> - Postingan <?= $story['username'] ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>
<body class="bg-slate-200 dark:bg-slate-800">
<main class="min-h-screen">
  <div class="grid md:grid-cols-4">

    <div class="z-10"></div>

    <div class="lg:col-span-2 md:col-span-3">
      <div class="header px-8 pt-6 flex flex-col gap-2">
        <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center rounded-sm px-8 py-4">
          <a href="<?= BASE_URL ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
              <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z"/>
            </svg>
          </a>
        </div>
      </div>
      <?php include('../public/components/storyCard.php') ?>
      <div class="px-8 my-2">
        <div class="bg-slate-50 dark:bg-slate-900 shadow-lg px-8 py-4">
          <h1 class="text-2xl font-semibold dark:text-white">Komentar</h1>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="flex items-center gap-2 mt-2">
              <label for="comment-photo" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
                  <path d="M6.502 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                  <path d="M14 14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5V14zM4 1a1 1 0 0 0-1 1v10l2.224-2.224a.5.5 0 0 1 .61-.075L8 11l2.157-3.02a.5.5 0 0 1 .76-.063L13 10V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4z"/>
                </svg>
              </label>
                <input type="file" id="comment-photo" name="comment-photo" class="hidden">
                <p id="result"></p>
                <input type="text" name="comment" placeholder="Tulis Komentar" autocomplete="off" class="bg-slate-200 w-full rounded-full py-2 indent-4">
                <button type="submit" name="btn-comment" class="w-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
                    <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                  </svg>
                </button>
              </div>
            </form>
            <hr class="my-3">
          <?php foreach($getComment as $setComment): ?>
            <form action="" method="post">
            <div class="comment my-4">
              <input type="hidden" name="comment_id" value="<?= $setComment['comment_id'] ?>">
              <input type="hidden" name="comment_image" value="<?= $setComment['comment_image'] ?>">
              <div class="post-head flex gap-4">
                <a href="" class="h-12 w-14 overflow-hidden rounded-full">
                  <img class="h-full w-full object-cover" src="<?= PHOTO_URL . '/' . $setComment['photo'] ?>" alt="">
                </a>
                <div class="bg-slate-300 dark:bg-slate-700 dark:text-white w-full shadow-md p-2 rounded-lg">
                  <div class="flex items-center gap-1">
                    <h1 class="font-semibold"><?= $setComment['username'] ?></h1>
                    <?= ($setComment['verify'] > 0) ? '<img src="' . ICON_URL . '/verify-sm.svg" alt="">' : '' ?>
                    <?= ($setComment['username'] === $_SESSION['username']) 
                      ? '<button type="submit" name="comment-edit" class="ml-auto text-green-500">Edit</button>
                          <button type="submit" name="comment-delete" class="text-red-500">Hapus</button>'
                      : ''
                    ?>
                  </div>
                  <small class="text-xs">
                    <?php
                      $timeFromDb = strtotime($setComment['comment_at']);
                      $currentDate = time();
                      $timeDifference = $currentDate - $timeFromDb;

                      if ($timeDifference < 60) {
                          echo "Baru saja";
                      } elseif ($timeDifference < 3600) {
                          echo floor($timeDifference / 60) . ' menit yang lalu';
                      } elseif ($timeDifference < 86400) {
                          echo floor($timeDifference / 3600) . ' jam yang lalu';
                      } elseif (date("Y-m-d") == date('Y-m-d', $timeFromDb)) {
                          echo 'Hari ini';
                      } else {
                          echo date('d F Y', $timeFromDb);
                      }
                    ?>
                  </small>
                  <hr class="my-2">
                  <p>
                    <!-- <a href="" class="text-green-600 hover:text-green-400 duration-200 font-semibold">Danang Setiadi</a> -->
                    <?= $setComment['comment_text'] ?>
                  </p>
                  <?= (empty($setComment['comment_image'])) 
                    ? '' 
                    : '<div class="w-44 rounded-md mt-3 overflow-hidden">
                        <a href="">
                          <img src="' . COMMENT_URL . '/' . $setComment["comment_image"] . '" class="w-full object-cover" alt="">
                        </a>
                      </div>
                  ' ?>
                  <div class="flex mt-3 gap-8">
                    <a href="" class="font-semibold hover:text-slate-700 dark:hover:text-slate-300 duration-200">Like</a>
                    <a href="<?= BASE_URL ?>/story/reply/?u=<?= $setComment['username'] ?>&s=<?= $setComment['story_id'] ?>&c=<?= $setComment['comment_id'] ?>" class="font-semibold hover:text-slate-700 dark:hover:text-slate-300 duration-200">Reply(2)</a>
                  </div>
                </div>
              </div>
            </div>
            </form>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <div class="lg:col-span-1 ml-8 lg:block hidden -z-10 fixed right-6 top-6 bottom-6 w-80 overflow-hidden bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg">
      <p class="p-10">Anggep aja iklan</p>
    </div>

  </div>
  </main>

  <script src="../public/library/jquery/dist/jquery.min.js"></script>
  <script src="<?= SCRIPT_URL ?>/like.js"></script>
  <script>
      var fileInput = document.getElementById('comment-photo');
      var result = document.getElementById('result');

      fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
        result.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="fill-green-400" viewBox="0 0 16 16">
                              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            `;
        }
      })
  </script>

</body>
</html>