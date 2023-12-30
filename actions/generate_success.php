<?php
require_once '../apps/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/public/styles/output.css">
</head>
<body class="bg-slate-200 flex justify-center items-center min-h-screen">
  <main class="bg-slate-50 p-10 shadow-lg">
    <h1 class="text-2xl">Database dan beberapa table yang dibutuhkan berhasil digenerate!</h1>
    <h1 class="mt-8 mb-2">Kembali ke halaman utama sekarang?</h1>
    <a href="<?= BASE_URL ?>" class="px-10 py-1 bg-slate-900 text-white">Ya</a>
  </main>
</body>
</html>