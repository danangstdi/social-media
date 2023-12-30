<?php

session_start();

require_once '../apps/config.php';
require_once '../apps/database/Database.php';
require_once '../apps/database/Story.php';
require_once '../apps/database/Mix.php';

$db = new Database();
$mix = new Mix($db->getConnection());
$story = new Story($db->getConnection());

if (isset($_GET['s'])) {
  if ($story->deleteStory() > 0) {
    if (isset($_GET['page'])) {
      header("Location:" . BASE_URL . '/' . $_GET['page'] . '?u=' . $_SESSION['username']);
      exit; 
    } else {
      header("Location:" . BASE_URL);
      exit; 
    }
  }
} 

