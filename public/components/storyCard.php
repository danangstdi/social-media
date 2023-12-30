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
                <button class="ml-auto cursor-pointer relative" onclick="
                  if (document.getElementById('story_option<?= $story['story_id'] ?>').classList.contains('hidden')) {
                    document.getElementById('story_option<?= $story['story_id'] ?>').classList.remove('hidden');
                  } else {
                    document.getElementById('story_option<?= $story['story_id'] ?>').classList.add('hidden');
                  }
                ">
                  <svg class="dark:fill-white" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                  </svg>
                  <div id="story_option<?= $story['story_id'] ?>" class="absolute hidden right-0 top-7">
                    <input type="hidden" name="option-id" value="<?= $story['story_id'] ?>">
                    <ul>
                      <?=
                      ($story['username'] == $username)
                        ? '<a href="'. BASE_URL .'/story/edit/?u='. $username .'&s='. $story['story_id'] .'">
                            <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Edit</li>
                          </a>
                          <a href="' . BASE_URL . '/actions/delete-story.php?s=' . $story['story_id'] . '&img='.$story['story_image']. '&file=' . $story['story_file'] .'">
                            <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">Hapus</li>
                          </a>'
                        : ''
                      ?>
                      <a href="">
                        <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">
                          Favorite
                        </li>
                      </a>
                      <a href="">
                        <li class="px-10 py-1 bg-slate-50 dark:bg-slate-950 hover:dark:bg-slate-800 hover:bg-slate-200 duration-200 shadow-md">
                          Laporkan
                        </li>
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
                <?php 
                  if (!empty($story['story_file'])) {
                    $storyFile = $story['story_file'];

                    $icon = explode('.', $storyFile);
                    $icon = strtolower(end($icon));

                    $iconValidate = ['ai','css','csv','docx','exe','html','java','js','json',
                                    'mp3','mp4','pdf','php','pptx','psd','py','sql','txt','xlsx','zip'];
                    (in_array($icon, $iconValidate)) ? $iconFinal = $icon : $iconFinal = 'file';

                    $fileUrl = STORYFILE_URL;
                    $iconUrl = ICON_URL;
                    
                    $component = "
                    <div class='bg-slate-400 dark:bg-slate-800 text-white p-4 mt-4 rounded-md flex items-center justify-between'>
                      <div class='flex items-center gap-2'>
                        <img id='icon-preview' src='$iconUrl/files/$iconFinal.svg' alt=''>
                        <p class='text-xs md:text-sm lg:text-base'>$storyFile</p>
                      </div>
                      <a download href='$fileUrl/$storyFile'>
                        <svg class='md:hidden' xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' viewBox='0 0 16 16'>
                          <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z'/>
                        </svg>
                        <span class='hover:text-slate-300 duration-200 hidden md:block'>
                          Download
                        </span>
                      </a>
                    </div>
                    ";
                    echo $component;
                  }
                ?>
              </div>
              <div class="post-reaction flex justify-between md:justify-normal md:gap-4 items-center">
                <button type="submit" name="like<?= $story['story_id'] ?>" id="like<?= $story['story_id'] ?>" data-btn="like<?= $story['story_id'] ?>" data-story_id="<?= $story['story_id'] ?>" data-username="<?= $_SESSION['username'] ?>" 
                onclick="
                const btn = document.getElementById('like<?= $story['story_id'] ?>');
                if (btn.classList.contains('bg-green-400')) {
                  btn.classList.remove('bg-green-400');
                  btn.classList.add('bg-slate-200');
                  btn.classList.add('dark:bg-slate-500');
                } else {
                  btn.classList.remove('bg-slate-200');
                  btn.classList.remove('dark:bg-slate-500');
                  btn.classList.add('bg-green-400');
                }
                "
                <?php 
                // Cek Like
                $story_id = $story['story_id'];
                $check = $mix->query("SELECT * FROM story_like WHERE story_id = '$story_id' AND username = '$username'")
                ?>
                class="<?= ($check) ? 'bg-green-400' : 'bg-slate-200 dark:bg-slate-500' ?>
                px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/like.svg" alt="">
                </button>
                <!-- <a href="" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/dislike.svg" alt="">
                </a> -->
                <a href="<?= BASE_URL ?>/story/?u=<?= $story['username'] ?>&s=<?= $story['story_id'] ?>" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/comment.svg" alt="">
                </a>
                <button type="button" onclick="document.getElementById('share<?= $story['story_id'] ?>').classList.remove('hidden')" class="bg-slate-200 dark:bg-slate-500 px-8 py-2 hover:shadow-md rounded-sm">
                  <img src="<?= ICON_URL ?>/share.svg" alt="">
                </button>
              </div>
            </div>
          </div>

          <!-- Share Popup -->
          <div id="share<?= $story['story_id'] ?>" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 bg-slate-700 p-6 shadow-lg rounded-lg">
            <button type="button" onclick="document.getElementById('share<?= $story['story_id'] ?>').classList.add('hidden')" class="bg-slate-500">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/></svg>
            </button>
            <h1 class="text-2xl text-white text-center">Bagikan!</h1>
            <div class="flex items-center gap-2 mt-4">
              <input id="story-link<?= $story['story_id'] ?>" type="text" value="<?= BASE_URL ?>/story/?u=<?= $story['username'] ?>&s=<?= $story['story_id'] ?>" readonly class="bg-slate-200 px-2" >
              <button type="button" id="btn-copy<?= $story['story_id'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16"><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/></svg>
              </button>
            </div>
            <div class="flex items-center gap-2 mt-4">
              <a href="https://api.whatsapp.com/send?text=<?= BASE_URL ?>/story/?u=<?= $story['username'] ?>%26s=<?= $story['story_id'] ?>" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-green-400" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
              </a>
              <a href="https://t.me/share/url?url=<?= BASE_URL ?>/story/?u=<?= $story['username'] ?>%26s=<?= $story['story_id'] ?>" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="fill-blue-400" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/></svg>
              </a>
            </div>
          </div> 

          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const btnCopy = document.getElementById('btn-copy<?= $story['story_id'] ?>');
              const storyLink = document.getElementById('story-link<?= $story['story_id'] ?>');

              btnCopy.addEventListener('click', function () {
                // Salin teks URL ke clipboard
                storyLink.select();
                document.execCommand('copy');

                // Response
                btnCopy.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16"><path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708Z"/></svg>`;
              });
            });
          </script>