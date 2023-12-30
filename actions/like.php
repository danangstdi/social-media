<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Mix.php';

$db = new Database();
$mix = new Mix($db->getConnection());

if (isset($_POST['action'])) {
  if ($_POST['action'] === 'like') {
    $username = $_POST['username'];
    $id = $_POST['story_id'];
    $isLike = $mix->query("SELECT * FROM story_like WHERE username = '$username' AND story_id = '$id'");
    if ($isLike) {
      $mix->query("DELETE FROM story_like WHERE username = '$username' AND story_id = '$id'");
    } else {
      $story_like = 'like_' . $username . '_' . uniqid();
      $mix->query("INSERT INTO story_like (story_like_id, story_id, username) VALUES ('$story_like', '$id', '$username')");
    }
  }
}