<?php

session_start();

require_once '../../apps/config.php';
require_once '../../apps/database/Database.php';
require_once '../../apps/database/Story.php';
require_once '../../apps/database/Mix.php';

(!isset($_SESSION['login'])) ? header(' ' . BASE_URL . '/error/unlogged') : '';

$db = new Database();
$story = new Story($db->getConnection());
$mix = new Mix($db->getConnection());
$s = $_GET['s'];

if (isset($_POST['btn-submit'])) {
  $story->editStory($_POST);
}

$user = $mix->query("SELECT * FROM story JOIN users ON story.username = users.username WHERE story.story_id = '$s'")[0];
?>

<html lang="en" id="theme-switch" class="<?= $user['theme'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>
<body class="bg-slate-200 dark:bg-slate-800">
<main class="min-h-screen">
  <div class="grid md:grid-cols-4">

    <div class="z-10"></div>

    <div class="lg:col-span-2 md:col-span-3 relative">

      <div class="header px-8 pt-6 flex flex-col gap-2">
        <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center rounded-sm px-8 py-4">
        <a href="<?= BASE_URL ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
              <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z"/>
            </svg>
          </a>
        </div>
      </div>
      
      <div class="px-8 my-2">
        <div class="bg-slate-50 dark:bg-slate-900 shadow-lg px-8 py-4">
          <div class="post-head flex items-center gap-4">
            <a href="" class="h-12 w-12 overflow-hidden rounded-full">
              <img class="h-full w-full object-cover" src="<?= PHOTO_URL ?>/<?= $user['photo'] ?>" alt="">
            </a>
            <div>
              <h1 class="font-semibold dark:text-white"><?= $user['username'] ?></h1>
              <small class="text-xs text-white px-2 bg-slate-400">Public</small>
            </div>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="flex flex-col gap-2 mt-3">
              <textarea class="p-2 border border-slate-900 mt-4 dark:bg-slate-800 dark:text-white" name="caption" placeholder="Caption" id="caption" rows="5"><?= $user['story_caption'] ?></textarea>
              <button type="submit" name="btn-submit" class="bg-slate-900 dark:bg-green-400 hover:dark:bg-green-300 hover:bg-slate-700 duration-200 text-white py-2 rounded-md">Submit</button>
            </div>
          </form>
        </div>
      </div>

    </div>

    <div class="lg:col-span-1 ml-8 lg:block hidden -z-10 fixed right-6 top-6 bottom-6 w-80 overflow-hidden bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg">
      <p class="p-10">Anggep aja iklan</p>
    </div>

  </div>
  </main>

  <!-- <script>
    function setForm(getInput) {
      const setInput = document.getElementById(getInput);

      if (setInput.classList.contains('hidden')) {
        setInput.classList.remove('hidden');
        setInput.classList.add('block');
      } else {
        setInput.classList.add('hidden');
        setInput.classList.remove('block');
      }
    }
  </script> -->
</body>
</html>