<?php

function sidebar($home, $profile, $games, $trade) {
  $username = $_SESSION['username'];
  $icon_url = ICON_URL ;
  $base_url = BASE_URL ;
  $components = 
  "
      <div id=\"sidebar\" class=\"bg-slate-900 -ml-36 md:-ml-0 duration-300 fixed bottom-6 top-6 left-6 rounded-lg p-3 md:p-6\">
        <h1 class=\"cursor-default text-center text-2xl hidden lg:block text-white lg:px-16 xl:px-20 font-semibold\">Nolepverse</h1>
        <hr class=\"opacity-20 my-6\">
        <ul class=\"flex flex-col gap-3\">
          <a href=\"$base_url/\" class=\"$home hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
            <img src=\"$icon_url/home.svg\" alt=\"\">
            <li class=\"font-semibold text-white hidden lg:block\">Home</li>
          </a>
          <a href=\"$base_url/profile/?u=$username\" class=\"$profile hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
            <img src=\"$icon_url/profile.svg\" alt=\"\">
            <li class=\"font-semibold text-white hidden lg:block\">Profile</li>
          </a>
          <a href=\"\" class=\"$games hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
            <img src=\"$icon_url/games.svg\" alt=\"\">
            <li class=\"font-semibold text-white hidden lg:block\">Games</li>
          </a>
          <a href=\"\" class=\"$trade hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
            <img src=\"$icon_url/trade.svg\" alt=\"\">
            <li class=\"font-semibold text-white hidden lg:block\">Trade</li>
          </a>
            <button type=\"button\" onclick=\"switchMode()\" data-username=\"$username\" class=\"themeToggler hover:bg-green-400 gap-3 flex items-center duration-300 py-4 px-4 mx-2 rounded-lg\">
              <img id=\"icon-mode\" src=\"$icon_url/light.svg\" alt=\"\">
              <li id=\"text-mode\" class=\"font-semibold text-white hidden lg:block\">Light Mode</li>
            </button>
        </ul>
      </div>
  ";
  echo $components;
}