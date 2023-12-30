<?php
session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Mix.php';
require_once '../apps/database/Users.php';

if (!isset($_SESSION['login'])) {
  header('Location: ' . BASE_URL . '/auth/login');
}

$db = new Database();
$mix = new Mix($db->getConnection());
$users = new Users($db->getConnection());

$username = $_SESSION['username'];
$user = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];

$params_q = "";
if (isset($_GET['q'])) {
  $params_q = $_GET['q'];
  $stories = $mix->query("SELECT * FROM story JOIN users ON story.story_caption LIKE '%$params_q%'");
} else if (isset($_GET['u'])) {
  $params_u = $_GET['u'];
  $stories = $mix->query("SELECT * FROM story JOIN users ON users.username LIKE '%$params_u%'");
}

if (isset($_POST['btn-search'])) {
  if (isset($_GET['q'])) {
    header('Location:' . BASE_URL . '/search/?q=' . $_POST['search-form']);
  } elseif (isset($_GET['u'])) {
    header('Location:' . BASE_URL . '/search/?u=' . $_POST['search-form']);
  }
}



?>

<html lang="en" id="theme-switch" class="<?= $user['theme'] ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nolepverse - Search</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>

<body class="bg-slate-200 dark:bg-slate-800">
  <main class="min-h-screen">


    <div class="grid md:grid-cols-4">

      <div class="z-10"></div>

      <div class="lg:col-span-2 md:col-span-3 relative">

        <div class="header px-8 pt-6 flex flex-col gap-2">
          <form action="" method="post">

          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center gap-6 rounded-sm px-8 py-4">
            <a href="<?= BASE_URL ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
                <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z" />
              </svg>
            </a>
              <div class="flex items-center gap-2 w-full">
                <input type="text" name="search-form" value="<?= (isset($_GET['u']) ? $_GET['u'] : $_GET['q']) ?>" placeholder="Apa yang anda cari?" class="bg-transparent border border-slate-700 dark:border-slate-400 dark:text-white py-2 w-full ps-4 rounded-lg" />
                <button name="btn-search" class="bg-green-400 py-2 px-6 rounded-lg">Cari</button>
              </div>
            </div>
          <div class="flex items-center gap-6 rounded-sm py-2">
              <div class="flex items-center gap-2 w-full dark:text-white">
                <a href="<?=BASE_URL?>/search/?q=" class="bg-slate-50 dark:bg-slate-900 px-2 py-1">
                  <small>Berdasarkan Postingan</small>
                </a>
                <a href="<?=BASE_URL?>/search/?u=" class="bg-slate-50 dark:bg-slate-900 px-2 py-1">
                  <small>Berdasarkan Username</small>
                </a>
              </div>
            </div>
            
          </form>
        </div>

        <?php if(!empty($_GET['u'])): ?>
          <?php foreach($stories as $user): ?>
            <div class="header px-8 pt-6 flex flex-col gap-2">
              <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center gap-6 rounded-sm px-8 py-4">
                <a href="<?= BASE_URL ?>/profile/?u=<?= $user['username'] ?>" class="h-12 w-12 overflow-hidden rounded-full">
                  <img class="h-full w-full object-cover" src="<?= PHOTO_URL . '/' . $user['photo'] ?>" alt="">
                </a>
                <div class="flex flex-col">
                  <a href="<?= BASE_URL ?>/profile/?u=<?= $user['username'] ?>">
                    <div class="flex items-center gap-1">
                      <h1 class="font-semibold dark:text-white"><?= $user['username'] ?></h1>
                      <?= ($user['verify'] > 0) ? '<img src="' . ICON_URL . '/verify-sm.svg" alt="">' : '' ?>
                    </div>
                  </a>
                  <a href="">
                    <small class="dark:text-white">
                      3000 Pengikut
                    </small>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>


      <?php if(!empty($_GET['q'])): ?>
        <?php foreach ($stories as $story) : ?>
          <div class="px-8 my-2">
            <div class="bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg px-8 py-4">
              <div class="post-head flex items-center gap-4">
                <a href="<?= BASE_URL ?>/profile/?u=<?= $story['username'] ?>" class="h-12 w-12 overflow-hidden rounded-full">
                  <img class="h-full w-full object-cover" src="<?= PHOTO_URL . '/' . $story['photo'] ?>" alt="">
                </a>
                <a href="<?= BASE_URL ?>/profile/?u=<?= $story['username'] ?>">
                  <div class="flex items-center gap-1">
                    <h1 class="font-semibold"><?= $story['username'] ?></h1>
                    <?= ($story['verify'] > 0) ? '<img src="' . ICON_URL . '/verify-sm.svg" alt="">' : '' ?>
                  </div>
                  <small class="text-xs">
                  <?php
                    $storyDate = strtotime($story['story_at']);
                    $currentDate = time();
                    $timeDifference = $currentDate - $storyDate;

                    if ($timeDifference < 60) {
                        echo "Baru saja";
                    } elseif ($timeDifference < 3600) {
                        echo floor($timeDifference / 60) . ' menit yang lalu';
                    } elseif ($timeDifference < 86400) {
                        echo floor($timeDifference / 3600) . ' jam yang lalu';
                    } elseif (date("Y-m-d") == date('Y-m-d', $storyDate)) {
                        echo 'Hari ini';
                    } else {
                        echo date('d F Y', $storyDate);
                    }
                  ?>

                  </small>
                </a>
                <button class="ml-auto cursor-pointer" onclick="
                  if (document.getElementById('story_option<?= $story['story_id'] ?>').classList.contains('hidden')) {
                    document.getElementById('story_option<?= $story['story_id'] ?>').classList.remove('hidden');
                  } else {
                    document.getElementById('story_option<?= $story['story_id'] ?>').classList.add('hidden');
                  }
                ">
                  <svg class="dark:fill-white" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                  </svg>
                  <div id="story_option<?= $story['story_id'] ?>" class="absolute hidden right-10">
                    <input type="hidden" name="option-id" value="<?= $story['story_id'] ?>">
                    <ul>
                      <?=
                      ($story['username'] == $username)
                        ? '<a href="'. BASE_URL .'/story/edit/?u='. $username .'&s='. $story['story_id'] .'">
                            <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Edit</li>
                          </a>
                          <a href="' . BASE_URL . '/actions/delete-story.php?s=' . $story['story_id'] . '&img='.$story['story_image'].'">
                            <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Hapus</li>
                          </a>'
                        : ''
                      ?>
                      <a href="">
                        <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Favorite</li>
                      </a>
                      <a href="">
                        <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Salin link</li>
                      </a>
                      <a href="">
                        <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Laporkan</li>
                      </a>
                    </ul>
                  </div>
                </button>
              </div>




              <div class="post-caption my-8">
                <?= $story['story_caption'] ?>
                <p>
                  <?php $hastags = explode(" ", $story['story_hastag']) ?>
                  <?php foreach ($hastags as $hastag) : ?>
                    <a href="search/?q=<?= $hastag ?>" class="text-green-500"><?= $hastag ?></a>
                  <?php endforeach; ?>
                </p>
                <a href="">
                  <img src="<?= STORYIMAGE_URL ?>/<?= $story['story_image'] ?>" class="mt-2" alt="">
                </a>
              </div>




              <div class="post-reaction flex justify-between md:justify-normal md:gap-4 items-center">
                <button type="submit" name="like<?= $story['story_id'] ?>" id="like<?= $story['story_id'] ?>" data-btn="like<?= $story['story_id'] ?>" data-story_id="<?= $story['story_id'] ?>" data-username="<?= $_SESSION['username'] ?>" 
                class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/like.svg" alt="">
                </button>
                <a href="" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/dislike.svg" alt="">
                </a>
                <a href="<?= BASE_URL ?>/story/?u=<?= $story['username'] ?>&s=<?= $story['story_id'] ?>" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/comment.svg" alt="">
                </a>
                <a href="" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/share.svg" alt="">
                </a>
              </div>




            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>




      </div>



      <div class="lg:col-span-1 ml-8 lg:block hidden -z-10 fixed right-6 top-6 bottom-6 w-80 overflow-hidden bg-slate-50 shadow-lg dark:bg-slate-900 dark:text-white">
        <p class="p-10">Anggep aja iklan</p>
      </div>


    </div>
  </main>

  <script src="public/library/jquery/dist/jquery.min.js"></script>
  <script src="<?= SCRIPT_URL ?>/like.js"></script>
  <script src="<?= SCRIPT_URL ?>/themeMode.js"></script>
  <script src="<?= SCRIPT_URL ?>/sidebar.js"></script>
</body>

</html>