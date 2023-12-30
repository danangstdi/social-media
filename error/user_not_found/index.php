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
    User not found
  </p>
  <div class="text-slate-500 mt-4">
    <a href="<?= BASE_URL ?>/" class="hover:text-slate-300 duration-200">Back to home</a>
  </div>
</body>
</html>