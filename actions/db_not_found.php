<?php

require_once '../apps/database/Database.php';
require_once '../apps/config.php';

$db = new Database();

if (isset($_POST['btn-confirm'])) {
  $db->getConnection();
  $db->generate();
  header('Location:'.BASE_URL.'/actions/generate_success.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warning</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
</head>
<body class="bg-slate-200 flex justify-center items-center min-h-screen">
  <main class="bg-slate-50 p-10 shadow-lg">
    <h1 class="text-2xl">Database dan beberapa table tidak ditemukan!</h1>
    <h1 class="mt-8">Apakah anda ingin instalasi database secara otomatis sekarang?</h1>
    <form action="" method="post">
      <button type="submit" name="btn-confirm" class="px-10 py-1 bg-slate-900 mt-2 text-white">Ya</button>
    </form>
  </main>
</body>
</html>