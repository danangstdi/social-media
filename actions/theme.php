<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Mix.php';

$db = new Database();
$mix = new Mix($db->getConnection());

if (isset($_POST['action']) && $_POST['action'] === 'switchTheme') {
  if (isset($_POST['action']) && $_POST['action'] === 'switchTheme') {
    $username = $_SESSION['username'];
    $user = $mix->query("SELECT theme FROM users WHERE username = '$username'")[0];
    if ($user['theme'] == 'light') {
        $mix->query("UPDATE users SET theme = 'dark' WHERE username = '$username'");
    } else {
        $mix->query("UPDATE users SET theme = 'light' WHERE username = '$username'");
    }
  }
}