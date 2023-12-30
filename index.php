<?php
session_start();

require_once 'apps/config.php';
require_once 'apps/database/Database.php';
require_once 'apps/database/Mix.php';
require_once 'apps/database/Users.php';

// Cek Session user yang masuk
(!isset($_SESSION['login'])) ? header('Location: ' . BASE_URL . '/auth/login') : '';

$db = new Database();
$mix = new Mix($db->getConnection());
$users = new Users($db->getConnection());

$username = $_SESSION['username'];
$user = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];
$stories = $mix->query("SELECT * FROM story JOIN users ON story.username = users.username ORDER BY RAND()");
?>

<html lang="en" id="theme-switch" class="<?= $user['theme'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?> - Home</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
</head>
<body class="bg-slate-200 dark:bg-slate-800">

  <main class="min-h-screen">
    <div class="grid md:grid-cols-4">

      <div class="z-10">
        <?php $mix->sidebar('bg-green-400', '', '', '', $user['theme']) ?>
      </div>

      <div class="lg:col-span-2 md:col-span-3 relative">
        <div class="header px-8 pt-6 flex flex-col gap-2">
          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center rounded-sm px-8 py-4">
            <h1 class="text-xl font-semibold cursor-default dark:text-white">
              <?= APP_NAME ?>
            </h1>
            <div class="flex items-center ml-auto gap-4">
              <a href="<?= BASE_URL ?>/search/?q=">
                <?= SEARCH_ICON ?>
              </a>
              <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill dark:fill-white" viewBox="0 0 16 16">
                  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z" />
                </svg>
              </a>
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
            <a href="<?= BASE_URL ?>/profile/?u=<?= $_SESSION['username'] ?>" class="h-12 w-14 overflow-hidden rounded-full">
              <img class="h-full w-full object-cover" src="<?= PHOTO_URL ?>/<?= $user['photo'] ?>" alt="">
            </a>
            <a href="<?= BASE_URL ?>/story/create/?u=<?= $username ?>" class="border border-slate-700 dark:border-slate-400 dark:text-white py-2 w-full ps-4 rounded-lg">Apa yang anda pikirkan?</a>
          </div>
        </div>
        <?php 
        foreach ($stories as $story):
          include('./public/components/storyCard.php');
        endforeach; 
        ?>
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