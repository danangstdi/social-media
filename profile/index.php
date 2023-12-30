<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Mix.php';

if (!isset($_SESSION['login'])) {
  header('Location: ' . BASE_URL . '/auth/login');
}

$db = new Database();
$mix = new Mix($db->getConnection());
$username = $_GET['u'];
$userThemes = $_SESSION['username'];

if (isset($_GET['u'])) {
  $checkUser = $mix->query("SELECT username FROM users WHERE username = '$username'");
  if (!$checkUser) {
    header('Location:' . BASE_URL . '/error/user_not_found');
  }
}

$followText = "Follow";
if ($mix->query("SELECT * FROM follow WHERE follower_username = '$userThemes' AND followed_username = '$username'")) {
  $followText = "Unfollow";
}

$user = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];
$story_total = $mix->query("SELECT  COUNT(*) as story_total FROM story WHERE username = '$username'")[0];
$follower_total = $mix->query("SELECT  COUNT(*) as follower_total FROM follow WHERE followed_username = '$username'")[0];
$followed_total = $mix->query("SELECT  COUNT(*) as followed_total FROM follow WHERE follower_username = '$username'")[0];
$userTheme = $mix->query("SELECT * FROM users WHERE username = '$userThemes'")[0];
$stories = $mix->query("SELECT * FROM story JOIN users ON story.username = users.username WHERE users.username = '$username' ORDER BY story.story_at DESC");
?>

<html lang="en" id="theme-switch" class="<?= $userTheme['theme'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>

<body class="bg-slate-200 dark:bg-slate-800">
  <main class="min-h-screen">
    <div class="grid md:grid-cols-4">

      <div class="z-10">
        <?php $mix->sidebar('', 'bg-green-400', '', '', $user['theme']) ?>
      </div>

      <div class="lg:col-span-2 md:col-span-3 relative">

        <!-- <div id="logout-toast" class="absolute -mt-44 opacity-0 bg-slate-900 duration-700 shadow-lg left-14 right-14 md:left-32 md:right-32 lg:left-24 lg:right-24 xl:left-40 xl:right-40 top-10 py-4 rounded-lg text-white"> -->
        <div id="logout-toast" class="fixed z-10 w-96 left-1/2 -translate-x-1/2 -mt-44 top-10 opacity-0 bg-slate-900 dark:bg-slate-800 duration-700 shadow-lg py-4 rounded-lg text-white">
          <div class="flex">
            <img src="<?= ICON_URL ?>/warning.svg" class="px-10" alt="">
            <div class="px-10">
              <p class="text-center">Yakin ingin keluar?</p>
              <hr class="opacity-20">
              <div class="flex gap-12 mt-4">
                <a href="<?= BASE_URL ?>/auth/logout.php" class="hover:text-slate-500 duration-200">Ya</a>
                <button onclick="logout()" class="hover:text-slate-500 duration-200">Tidak</button>
              </div>
            </div>
          </div>
        </div>

        <div class="header px-8 pt-6 flex flex-col gap-2">
          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center rounded-sm px-8 py-4">
            <!-- <a href="javascript:void(0)" onclick="window.history.back()"> -->
            <a href="<?= BASE_URL ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
                <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z" />
              </svg>
            </a>
            <div class="flex items-center ml-auto gap-4">
              <button onclick="logout()" class="text-red-500 hover:text-red-300 duration-200">Keluar</button>
              <label class="md:hidden stroke-black dark:stroke-white hamburger cursor-pointer">
                <input id="toggle" type="checkbox" class="hidden">
                <svg viewBox="0 0 32 32" class="h-7">
                  <path class="line stroke-2 fill-none stroke-black dark:stroke-white line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                  <path class="line stroke-2 fill-none stroke-black dark:stroke-white" d="M7 16 27 16"></path>
                </svg>
              </label>
            </div>
          </div>
          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center gap-6 rounded-sm px-8 py-4">
            <h1 class="font-semibold text-xl dark:text-white">
              <?= ($_GET['u'] == $_SESSION['username']) ? 'Selamat datang, ' . $user['username'] : 'Profile dari ' . $_GET['u'] ?>
            </h1>
          </div>
        </div>

        <div class="px-8">
          <div class="bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg my-2 pb-2">
            <div class="photo-card relative">
              <a href="">
                <div class="w-full h-56 md:h-80 overflow-hidden">
                  <img src="<?= BG_URL ?>/<?= $user['background'] ?>" class="w-full h-full object-cover" alt="">
                </div>
              </a>
              <a href="" class="absolute h-32 w-32 md:h-52 md:w-52 rounded-full overflow-hidden border-4 -bottom-12 md:-bottom-24 left-5">
                <img src="<?= PHOTO_URL ?>/<?= $user['photo'] ?>" class="h-full w-full object-cover" alt="">
              </a>
            </div>
            <div class="mt-14 md:mt-28 m-10">
              <div class="flex items-center gap-1 mb-2">
                <h1 class="font-semibold text-2xl"><?= $user['username'] ?></h1>
                <img src="<?= ICON_URL ?>/<?= ($user['verify'] > 0) ? 'verify.svg' : '' ?>" alt="">
              </div>
              <p class="bio"><?= $user['bio'] ?></p>
              <div class="statistic-card flex mt-10 justify-between md:justify-normal md:gap-20">
                <a href="" class="text-center font-semibold">
                  <p><?= $story_total['story_total'] ?></p>
                  <p>Postingan</p>
                </a>
                <a href="" class="text-center font-semibold">
                  <p id="follower_count" data-follower_count=<?= $follower_total['follower_total'] ?>><?= $follower_total['follower_total'] ?></p>
                  <p>Follower</p>
                </a>
                <a href="" class="text-center font-semibold">
                  <p><?= $followed_total['followed_total'] ?></p>
                  <p>Following</p>
                </a>
              </div>
              <!-- <form action="" method="post"> -->
                <div class="flex justify-between md:justify-normal md:gap-4 mt-4">

                  <?=
                  ($_SESSION['username'] == $username)
                    ?
                    '<a href="' . BASE_URL . '/profile/edit/?u=' . $_SESSION['username'] . '" 
                  class="bg-slate-900 dark:bg-slate-700 hover:bg-slate-600 hover:dark:bg-slate-400 duration-200 text-white px-7 md:px-10 py-1 rounded-sm">
                    Edit
                  </a>'
                    :
                    '<button type="submit" name="follow-btn" id="follow-btn" data-status="'. $followText .'" onclick="followStatus()" data-follower="'. $_SESSION['username'] .'" data-followed="'. $_GET['u'] .'"
                  class="follow-btn bg-slate-900 dark:bg-slate-700 hover:bg-slate-600 hover:dark:bg-slate-400 duration-200 text-white px-7 md:px-10 py-1 rounded-sm">
                    '. $followText .'
                  </button>'
                  ?>

                  <button class="bg-slate-900 dark:bg-slate-700 hover:bg-slate-600 hover:dark:bg-slate-400 duration-200 text-white px-7 md:px-10 py-1 rounded-sm">
                    Message
                  </button>
                  <button class="bg-slate-900 dark:bg-slate-700 hover:bg-slate-600 hover:dark:bg-slate-400 duration-200 text-white px-7 md:px-10 py-1 rounded-sm">
                    Social
                  </button>
                </div>
              <!-- </form> -->
            </div>
          </div>
        </div>

        <div class="px-8 my-2">
          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg px-8 py-2">
            <div class="flex">
              <a href="" class="text-slate-900 dark:text-white hover:text-slate-900 px-6 py-2 font-semibold duration-200">Postingan</a>
              <a href="" class="text-slate-500 hover:text-slate-900 hover:dark:text-slate-400 px-6 py-2 font-semibold duration-200">Gallery</a>
            </div>
          </div>
        </div>

        <?php
        foreach ($stories as $story):
          include('../public/components/storyCard.php');  
        endforeach; 
        ?>

      </div>

      <div class="lg:col-span-1 ml-8 lg:block hidden -z-10 fixed right-6 top-6 bottom-6 w-80 overflow-hidden bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg">
        <p class="p-10">Anggep aja iklan</p>
      </div>

    </div>
  </main>

  <script src="../public/library/jquery/dist/jquery.min.js"></script>
  <script src="<?= SCRIPT_URL ?>/follow.js"></script>
  <script src="<?= SCRIPT_URL ?>/sidebar.js"></script>
  <script src="<?= SCRIPT_URL ?>/themeMode.js"></script>
  <script src="<?= SCRIPT_URL ?>/like.js"></script>
  <script>
    function logout() {
      const toast = document.getElementById('logout-toast');
      if (toast.classList.contains('-mt-44', 'opacity-0')) {
        toast.classList.remove('-mt-44', 'opacity-0');
      } else {
        toast.classList.add('-mt-44', 'opacity-0');
      }
    }
  </script>
  <!-- <script>
    const btn = document.getElementById('follow-btn');

    btn.addEventListener('click', function() {
      btn.innerText = `Followed`;
    })
  </script> -->
</body>

</html>