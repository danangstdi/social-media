<?php

session_start();

require_once '../../apps/config.php';
require_once '../../apps/database/Database.php';
require_once '../../apps/database/Story.php';
require_once '../../apps/database/Mix.php';

if (!isset($_SESSION['login'])) {
  header(' ' . BASE_URL . '/error/unlogged');
}

$db = new Database();
$story = new Story($db->getConnection());
$mix = new Mix($db->getConnection());
$username = $_GET['u'];

if (isset($_POST['btn-submit'])) {
  $story->createStory($_POST);
}

$user = $mix->query("SELECT * FROM users WHERE username = '$username'")[0];
?>

<html lang="en" id="theme-switch" class="<?= $user['theme'] ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create</title>
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
              <img src="" id="create-preview" class="w-full hidden" alt="">
              <textarea class="p-2 border border-slate-900 mt-4 dark:bg-slate-800 dark:text-white" name="caption" placeholder="Caption" id="caption" rows="5"></textarea>
              <textarea class="p-2 hidden border border-slate-900 mt-1 dark:bg-slate-800 dark:text-white" name="tagar" placeholder="Tagar" id="tagar" rows="2"></textarea>
              <div id="file-parent" class="hidden p-2 bg-slate-600 text-white flex items-center gap-2 rounded-md">
                <img id="icon-preview" src="<?= ICON_URL ?>/files/file.svg" alt="">
                <span id="file-preview">No File Choosen</span>
              </div>
              <div class="flex gap-2">
                <button type="button" onclick="setForm('tagar')" class="bg-slate-900 dark:bg-slate-500 text-white px-4 py-1 rounded-md">
                  # Tagar
                </button>
                <label for="story-img" class="flex items-center gap-2 cursor-pointer bg-slate-900 dark:bg-slate-500 text-white px-4 py-1 rounded-md">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16"><path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/><path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10"/></svg>
                  Foto
                </label>
                <input type="file" accept="image/*" onchange="imgPreview('story-img', 'create-preview')" name="story-img" id="story-img" class="hidden">
                <?= ($user['level'] > 1) 
                ? '<label for="story-file" class="flex items-center gap-2 cursor-pointer bg-slate-900 dark:bg-slate-500 text-white px-4 py-1 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16"><path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4H2.19zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707z"/></svg>
                    File
                  </label>
                  <input type="file" name="story-file" id="story-file" class="hidden">'
                : ''
                ?>
              </div>
              <button type="submit" name="btn-submit" class="bg-slate-900 dark:bg-green-400 hover:dark:bg-green-300 hover:bg-slate-700 duration-200 text-white py-2 rounded-md">Posting</button>
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

  <script>
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
  </script>
  <script>
      const imgInput = document.getElementById('story-img');
      const imgResult = document.getElementById('create-preview');

      imgInput.addEventListener('change', function() {
        if (imgInput.files.length > 0) {
          imgResult.classList.remove('hidden');
        }
      })
  </script>
  <script>
      const fileInput = document.getElementById('story-file');
      const fileResult = document.getElementById('file-preview');
      const fileParent = document.getElementById('file-parent');
      const iconResult = document.getElementById('icon-preview');
      const acceptedIcon = ['ai','css','csv','docx','exe','html','java','js','json','mp3',
                            'mp4','pdf','php','pptx','psd','py','sql','txt','xlsx','zip'];

      fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
          fileName = fileInput.files[0].name;
          fileResult.innerHTML = fileName;
          fileParent.classList.remove('hidden');
          
          splitFileName = fileName.split('.');
          fileExtension = splitFileName[splitFileName.length - 1];
          if (acceptedIcon.includes(fileExtension)) {
              iconResult.src = '<?= ICON_URL ?>/files/' + fileExtension + '.svg';
          } else {
            iconResult.src = '<?= ICON_URL ?>/files/file.svg';
          }
        }
      })
  </script>
  <script src="<?= SCRIPT_URL ?>/imgPreview.js"></script>
</body>
</html>