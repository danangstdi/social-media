<?php

session_start();

require_once '../../apps/config.php';
require_once '../../apps/database/Database.php';
require_once '../../apps/database/Users.php';
require_once '../../apps/database/Mix.php';

$db = new Database();
$users = new Users($db->getConnection());
$mix = new Mix($db->getConnection());

if (isset($_POST['btn-delete'])) {
  $users->deleteAccount($_POST);
}

$username = $_SESSION['username'];
$user = $mix->query("SELECT * FROM users WHERE username = '$username'")
?>

<html lang="en" class="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Account</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>
<body class="bg-slate-200 dark:bg-slate-800 flex flex-col gap-3 items-center justify-center min-h-screen">
  <?php
    $mix->authToast('not-valid', 'red-400', 'Username atau Password tidak sesuai');
  ?>
  <div class="bg-slate-50 dark:bg-slate-900 dark:text-white p-4 w-96 shadow-xl">
    <h1 class="font-semibold text-3xl text-center">Delete Account</h1>
    <form action="" method="post">
      <input type="text" name="username" placeholder="Username" class="bg-slate-200 dark:bg-slate-800 mt-8 w-full py-2 indent-4 rounded-md">
      <div class="flex items-center mt-2">
        <input type="password" id="password" name="password" placeholder="Password" class="bg-slate-200 dark:bg-slate-800 w-full py-2 indent-4 rounded-md">
        <button onclick="passwordView()" type="button" id="btn-view" class="-ml-8">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
            <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
          </svg>
        </button>
      </div>
      <button type="submit" name="btn-delete" class="w-full bg-red-600 hover:bg-red-400 duration-200 text-white py-2 mt-6 rounded-md">Delete</button>
      <div class="flex">
        <a href="<?= BASE_URL ?>/" class="w-full text-center bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 text-white py-2 mt-2 rounded-md">Back to home</a>
      </div>
    </form>
  </div>

  <script src="<?= SCRIPT_URL ?>/passwordView.js"></script>
</body>
</html>