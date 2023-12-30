<?php

session_start();

require_once '../../apps/config.php';

?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/styles.css">
</head>
<body class="bg-slate-900 flex flex-col justify-center items-center min-h-full">
  <h1 class="text-5xl text-slate-400">404</h1>
  <p class="text-slate-400 text-lg text-center mt-4">
    Your status is Logged, <br>
    please logout if you want go to Login/Register Page
  </p>
  <div class="flex text-slate-500 mt-4 gap-16">
    <a href="<?= BASE_URL ?>/" class="hover:text-slate-300 duration-200">Back to home</a>
    <a href="<?= BASE_URL ?>/auth/logout.php" class="hover:text-slate-300 duration-200">Logout now</a>
  </div>
</body>
</html>