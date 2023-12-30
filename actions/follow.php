<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Mix.php';

$db = new Database();
$mix = new Mix($db->getConnection());

if (isset($_POST['action'])) {
  if ($_POST['action'] === 'follow') {
    $follower = $_POST['follower'];
    $followed = $_POST['followed'];
    $isFollow = $mix->query("SELECT * FROM follow WHERE follower_username = '$follower' AND followed_username = '$followed'");
    if ($isFollow) {
      $mix->query("DELETE FROM follow WHERE follower_username = '$follower' AND followed_username = '$followed'");
    } else {
      $mix->query("INSERT INTO follow VALUES ('', '$follower', '$followed', CURRENT_TIMESTAMP)");
    }
  }
}