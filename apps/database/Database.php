<?php

class Database {
  private $host = DB_HOST;
  private $username = DB_USERNAME;
  private $password = DB_PASSWORD;
  private $db_name = DB_NAME;
  public $conn;

  public function getConnection() {
    $this->conn = new mysqli($this->host, $this->username, $this->password);
    
    if (!$this->conn) {
      die('Koneksi gagal: ' . mysqli_connect_error());
    }
    
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->db_name'";
    $result = $this->conn->query($query);

    if ($result->num_rows > 0) {
      $this->conn->select_db($this->db_name);
      return $this->conn;
    } else {
        header('Location:' . BASE_URL . '/actions/db_not_found.php');
    }

    return $this->conn;
  }

  public function generate() {
    session_start();
    session_unset();
    
    $createDb = "CREATE DATABASE IF NOT EXISTS $this->db_name";
    $this->conn->query($createDb);

    $this->conn->select_db($this->db_name);

    $createUser =  "CREATE TABLE IF NOT EXISTS `users` (
      `username` varchar(30) PRIMARY KEY,
      `email` varchar(50),
      `password` varchar(255),
      `bio` text DEFAULT NULL,
      `photo` varchar(255) DEFAULT NULL,
      `background` varchar(255) DEFAULT NULL,
      `theme` varchar(20),
      `verify` varchar(20) DEFAULT NULL,
      `level` int(11),
      `last_login` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      `created_at` timestamp DEFAULT current_timestamp()
    )";
    $this->conn->query($createUser);

    $createStory = "CREATE TABLE IF NOT EXISTS `story` (
      `story_id` varchar(255) PRIMARY KEY,
      `username` varchar(30),
      `story_caption` text DEFAULT NULL,
      `story_image` varchar(255) DEFAULT NULL,
      `story_file` varchar(255) DEFAULT NULL,
      `story_hastag` varchar(255) DEFAULT NULL,
      `story_edit` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      `story_at` timestamp DEFAULT current_timestamp(),
      FOREIGN KEY (`username`) REFERENCES users(`username`) ON DELETE CASCADE
    )";
    $this->conn->query($createStory);

    $createStoryLike = "CREATE TABLE IF NOT EXISTS `story_like` (
      `story_like_id` varchar(255) PRIMARY KEY,
      `story_id` varchar(255),
      `username` varchar(30),
      `story_like_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      FOREIGN KEY (`story_id`) REFERENCES story(`story_id`) ON DELETE CASCADE,
      FOREIGN KEY (`username`) REFERENCES users(`username`) ON DELETE CASCADE
    )";
     $this->conn->query($createStoryLike);

     $createComment = "CREATE TABLE IF NOT EXISTS `comment` (
      `comment_id` varchar(255) PRIMARY KEY,
      `story_id` varchar(255),
      `username` varchar(30),
      `comment_text` text DEFAULT NULL,
      `comment_image` varchar(255) DEFAULT NULL,
      `comment_edit` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      `comment_at` timestamp DEFAULT current_timestamp(),
      FOREIGN KEY (`story_id`) REFERENCES story(`story_id`) ON DELETE CASCADE,
      FOREIGN KEY (`username`) REFERENCES users(`username`) ON DELETE CASCADE
    )";
    $this->conn->query($createComment);

    $createReply = "CREATE TABLE IF NOT EXISTS `reply` (
      `reply_id` varchar(255) PRIMARY KEY,
      `comment_id` varchar(255),
      `username` varchar(30),
      `reply_text` text DEFAULT NULL,
      `reply_image` varchar(255) DEFAULT NULL,
      `reply_edit` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      `reply_at` timestamp DEFAULT current_timestamp(),
      FOREIGN KEY (`comment_id`) REFERENCES comment(`comment_id`) ON DELETE CASCADE,
      FOREIGN KEY (`username`) REFERENCES users(`username`) ON DELETE CASCADE
    )";
    $this->conn->query($createReply);

    $createFollow = "CREATE TABLE IF NOT EXISTS `follow` (
      `follow_id` varchar(255) PRIMARY KEY,
      `follower_username` varchar(30),
      `followed_username` varchar(30),
      `follow_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      FOREIGN KEY (`follower_username`) REFERENCES users(`username`) ON DELETE CASCADE,
      FOREIGN KEY (`followed_username`) REFERENCES users(`username`) ON DELETE CASCADE
    )";
    $this->conn->query($createFollow);
  }
}