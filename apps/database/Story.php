<?php

class Story {
  private $table = 'story';
  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function createStory($data) {
    $caption = htmlspecialchars($data['caption']);
    $hastag = htmlspecialchars($_POST['tagar']);
    $username = $_GET['u'];
    $story_id = 'story_' . uniqid();

    $photoName = $_FILES['story-img']['name'];
    $imgTmpName = $_FILES['story-img']['tmp_name'];

    $fileName = $_FILES['story-file']['name'];
    $fileTmpName = $_FILES['story-file']['tmp_name'];
    
    // Gambar Extension
    // $jenisImgValid = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $jenisImg = explode('.', $photoName);
    $jenisImg = strtolower(end($jenisImg));

    // Cek Gambar
    (!empty($photoName)) ? $namaRandom = 'story_' . $username . '_' . uniqid() . '.' . $jenisImg : $namaRandom = NULL;
    
    // Cek File
    $fileName = str_replace(['?', '#', '/', '&'], '', $fileName);
    (!empty($fileName)) ? $namaFileRandom = uniqid() . '_' . $fileName : $namaFileRandom = NULL;
    
    $this->conn->query("INSERT INTO $this->table (story_id ,username, story_caption, story_image, story_file, story_hastag) VALUES ('$story_id', '$username', '$caption', '$namaRandom', '$namaFileRandom', '$hastag')");
    move_uploaded_file($imgTmpName, '../../public/assets/story-image/' . $namaRandom);
    move_uploaded_file($fileTmpName, '../../public/assets/story-file/' . $namaFileRandom);
    
    header('Location:' . BASE_URL);
    
    return mysqli_affected_rows($this->conn);
  }

  public function editStory($data) {
    $caption = htmlspecialchars($data['caption']);
    $s = $_GET['s'];

    $this->conn->query("UPDATE story SET story_caption = '$caption' WHERE story_id = '$s'");
    header('Location:' . BASE_URL . '/story/?u='. $_SESSION['username'] . '&s=' . $s);
    return mysqli_affected_rows($this->conn);
  }

  public function deleteStory() {
    $id = $_GET['s'];
    $img = $_GET['img'];
    $file = $_GET['file'];

    $imagePath = '../public/assets/story-image/' . $img;
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $filePath = '../public/assets/story-file/' . $file;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $this->conn->query("DELETE FROM story WHERE story_id = '$id'");
    return mysqli_affected_rows($this->conn);
  }

  public function storyLike($data) {
    $id = $data['like_id'];
    $query = mysqli_query($this->conn, "SELECT * FROM story_like WHERE story_id = $id");
    $storyId = mysqli_fetch_assoc($query)['story_id'];
    $checkbox = $data['like' . $storyId];

    var_dump($checkbox);
  }

}