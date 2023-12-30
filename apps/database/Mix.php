<?php

class Mix {
  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function query($sql) {

    $result = mysqli_query($this->conn, $sql);
    $rows = [];
    while($row = mysqli_fetch_assoc($result))
    {
      $rows[] = $row;
    }
    return $rows;
  }

  public function authToast($session, $color, $text) {
    $element = "<div class='text-xs flex justify-between items-center $color text-white w-96 p-3 text-center'>
      $text
      <a href=''>
        <img src='" . ICON_URL . "/x-small.svg' alt=''>
      </a>
    </div>";
    if (isset($_SESSION[$session])) {
      echo $element;
      unset($_SESSION[$session]);
    }
  }

  public function sidebar($home, $profile, $games, $trade, $theme) {
    $username = $_SESSION['username'];
    $app_name = APP_NAME;
    $icon_url = ICON_URL;
    $base_url = BASE_URL;
    $themetext = (ucfirst($theme) == "Dark") ? "Gelap" : "Terang";
    $components = 
    "
        <div id=\"sidebar\" class=\"bg-slate-900 -ml-36 md:-ml-0 duration-300 fixed bottom-6 top-6 left-6 rounded-lg p-3 md:p-6\">
          <h1 class=\"cursor-default text-center text-2xl hidden lg:block text-white lg:px-16 xl:px-20 font-semibold\">
            $app_name
          </h1>
          <hr class=\"opacity-20 my-6\">
          <ul class=\"flex flex-col gap-3\">
            <a href=\"$base_url/\" class=\"$home hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
              <img src=\"$icon_url/home.svg\" alt=\"\">
              <li class=\"font-semibold text-white hidden lg:block\">Beranda</li>
            </a>
            <a href=\"$base_url/profile/?u=$username\" class=\"$profile hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
              <img src=\"$icon_url/profile.svg\" alt=\"\">
              <li class=\"font-semibold text-white hidden lg:block\">Profil</li>
            </a>
            <a href=\"\" class=\"$games hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
              <img src=\"$icon_url/games.svg\" alt=\"\">
              <li class=\"font-semibold text-white hidden lg:block\">Permainan</li>
            </a>
            <a href=\"\" class=\"$trade hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
              <img src=\"$icon_url/trade.svg\" alt=\"\">
              <li class=\"font-semibold text-white hidden lg:block\">Marketplace</li>
            </a>
              <button type=\"button\" onclick=\"switchMode()\" data-username=\"$username\" class=\"themeToggler hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
                <img id=\"icon-mode\" src=\"$icon_url/$theme.svg\" alt=\"\">
                <li id=\"text-mode\" class=\"font-semibold text-white hidden lg:block\">Tema $themetext</li>
              </button>
          </ul>
        </div>
    ";
    echo $components;
  }

}