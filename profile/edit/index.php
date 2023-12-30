<?php

session_start();

require_once '../../apps/config.php';
require_once '../../apps/database/Database.php';
require_once '../../apps/database/Mix.php';
require_once '../../apps/database/Users.php';

if (!isset($_SESSION['login'])) {
  header('Location: ' . BASE_URL . '/error/unlogged');
}

$db = new Database();
$mix = new Mix($db->getConnection());
$users = new Users($db->getConnection());
$username = $_GET['u'];

($username !== $_SESSION['username']) ? header("Location:" . BASE_URL . "/profile?u=$username") : '';

$user = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];

if (isset($_POST['btn-bio'])) {
  $bio = $_POST['bio'];
  $username = $_GET['u'];
  $users->Update("UPDATE users SET bio = '$bio' WHERE username = '$username'", "bio-success");
}

if (isset($_POST['btn-photo'])) {
  $users->updatePhoto('photo', 'photo', 'photo-success');
}

if (isset($_POST['btn-bg'])) {
  $users->updatePhoto('bg', 'background', 'bg-success');
}

?>

<html lang="en" id="theme-switch" class="<?= $user['theme'] ?>">
<!-- <html lang="id"> -->
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

    <div class="lg:col-span-2 md:col-span-3">
      <div class="header px-8 pt-6 flex flex-col gap-2">
        <div class="bg-slate-50 dark:bg-slate-900 shadow-lg flex items-center rounded-sm px-8 py-4">
          <a href="<?= BASE_URL ?>/profile/?u=<?= $_GET['u'] ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="dark:fill-white" viewBox="0 0 16 16">
              <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z"/>
            </svg>
          </a>
        </div>
        <div class="bg-slate-50 shadow-lg flex items-center gap-6 rounded-sm px-8 py-4 dark:bg-slate-900">
          <h1 class="font-semibold text-xl dark:text-white">Edit Account</h1>
        </div>
      </div>

      <div class="px-8">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="bg-slate-50 dark:bg-slate-900 shadow-lg my-2 pb-2">
            <div class="photo-card relative">
              <label for="bg-label" class="cursor-pointer">
                <div class="w-full h-56 md:h-80 overflow-hidden relative">
                  <img id="bg-preview" src="<?= BG_URL . '/' . $user['background'] ?>" class="w-full h-full object-cover hover:brightness-75 duration-200" alt="">
                </div>
                <input type="file" onchange="imgPreview('bg-label', 'bg-preview')" name="bg" id="bg-label" class="hidden">
              </label>
              <label for="photo-label" class="cursor-pointer absolute h-32 w-32 md:h-52 md:w-52 rounded-full overflow-hidden border-4 -bottom-12 md:-bottom-24 left-5">
                <img id="photo-preview" src="<?= PHOTO_URL . '/' . $user['photo'] ?>" class="h-full w-full object-cover hover:brightness-75 duration-200" alt="">
              </label>
              <input type="file" onchange="imgPreview('photo-label', 'photo-preview')" name="photo" id="photo-label" class="hidden">
            </div>
            <div class="pt-14 md:pt-24 m-10 dark:text-white">
              <p class="text-sm">*Ketuk Foto Profil atau Background untuk memilih file</p>
              <p class="mb-4 text-sm">*Pastikan file berukuran kurang dari 2mb</p>
              <button type="submit" name="btn-photo" class="shadow-md w-full md:w-auto my-1 md:my-0 bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit Photo Profile</button>
              <button type="submit" name="btn-bg" class="shadow-md w-full md:w-auto my-1 md:my-0 bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit Background</button>
            </div>
          </div>

            <div class="bg-slate-50 dark:bg-slate-900 shadow-lg my-2 py-2">
                <div class="m-10">
                  <div class="flex flex-col mt-4">
                    <h1 class="text-2xl mb-6 font-semibold dark:text-white">Bio</h1>
                    <input type="text" placeholder="Bio" name="bio" value="<?= $user['bio'] ?>" class="w-full bg-transparent dark:bg-slate-800 dark:text-white border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>
                  
                <div class="mt-8">
                  <button type="submit" name="btn-bio" class="shadow-md bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit</button>
                </div>
              </div>
            </div>

              <div class="bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg my-2 py-1">
                <div class="m-10">
                  <h1 class="text-2xl font-semibold">Social</h1>
                  <div class="flex flex-col mt-4">
                    <label for="">Tiktok</label>
                    <input type="text" placeholder="Tiktok" name="tiktok" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>

                  <div class="flex flex-col mt-4">
                    <label for="">Facebook</label>
                    <input type="text" placeholder="Facebook" name="facebook" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>

                  <div class="flex flex-col mt-4">
                    <label for="">Youtube</label>
                    <input type="text" placeholder="Youtube" name="youtube" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>

                  <div class="flex flex-col mt-4">
                    <label for="">Instagram</label>
                    <input type="text" placeholder="Instagram" name="instagram" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>
                  
                  <div class="mt-8">
                    <button type="submit" class="shadow-md bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit</button>
                  </div>
                </div>
              </div>

              <div class="bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg my-2 py-1">
                <div class="m-10">
                  <h1 class="text-2xl font-semibold">Authentication</h1>
                  <div class="flex flex-col mt-5">
                    <label for="">Username</label>
                    <input type="text" placeholder="Username" name="username" value="<?= $user['username'] ?>" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>

                  <div class="flex flex-col mt-4">
                    <label for="">Email</label>
                    <input type="email" placeholder="Email" name="email" value="<?= $user['email'] ?>" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>
                
                  <div class="mt-8">
                    <button type="submit" class="shadow-md bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 hover:dark:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit</button>
                  </div>
                </div>
              </div>

              <div class="bg-slate-50 dark:bg-slate-900 dark:text-white shadow-lg my-2 py-1">
                <div class="m-10">
                  <h1 class="text-2xl font-semibold">Security</h1>

                  <div class="flex flex-col mt-4">
                    <label for="">Current Password</label>
                    <input type="password" placeholder="Current Password" name="current-pass" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>
                
                  
                  <div class="flex flex-col mt-4">
                    <label for="">New Password</label>
                    <input type="password" placeholder="New Password" name="new-pass" class="w-full bg-transparent dark:bg-slate-800 border border-slate-900 shadow-md py-2 indent-2 rounded-md">
                  </div>
                  
                  <div class="mt-8 flex gap-4">
                    <button type="submit" class="shadow-md bg-slate-900 dark:bg-slate-500 hover:bg-slate-700 dark:hover:bg-slate-400 duration-200 px-6 py-2 text-white rounded-md">Submit</button>
                    <a href="" class="shadow-md bg-green-400 hover:bg-green-300 duration-200 px-6 py-2 text-white rounded-md">Forgot your password?</a>
                  </div>
                </div>
              </div>
              
              <div class="bg-slate-50 dark:bg-slate-900 shadow-lg my-2 py-1">
                <div class="m-10">
                  <a href="<?= BASE_URL ?>/auth/delete-account" class="shadow-md bg-red-800 hover:bg-red-600 duration-200 px-6 py-2 text-white rounded-md">Delete this account</a>
                </div>
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

    <script src="<?= SCRIPT_URL ?>/imgPreview.js"></script>
</body>
</html>