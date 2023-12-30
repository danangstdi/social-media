<?php

class Reply {
  private $table = 'reply';
  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function createReply($data) {
    $comment = htmlspecialchars($data['reply']);
    $id = $_GET['c'];
    $username = $_SESSION['username'];
    $reply_id = 'reply_' . $username . '_' . uniqid();

    $photoName = $_FILES['reply-image']['name'];
    $photoSize = $_FILES['reply-image']['size'];
    $error = $_FILES['reply-image']['error'];
    $tmpName = $_FILES['reply-image']['tmp_name'];

    $jenisFile = explode('.', $photoName);
    $jenisFile = strtolower(end($jenisFile));

    if ( $photoSize > 3000000) { 
        echo "<script>
                alert('Ukuran file maksimal 3mb');
              </script>";
        return false;
    }

    if( $error !== 4 ) {
      $namaRandom = 'reply_' . $username . '_' . uniqid() . '.' . $jenisFile;
      move_uploaded_file($tmpName, '../../public/assets/comment-image/' . $namaRandom);
    } else {
      $namaRandom = NULL;
    }

    $this->conn->query("INSERT INTO $this->table VALUES ('$reply_id' , '$id', '$username', '$comment', '$namaRandom' , CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
    header('Location:' . BASE_URL . '/story/reply/?u=' . $_GET['u'] . '&s=' . $_GET['s'] . '&c=' . $id);
    return mysqli_affected_rows($this->conn);
  }

  public function deleteReply($data) {
    $id = $data['reply_id'];
    $img = $data['reply_image'];

    $imagePath = '../../public/assets/comment-image/' . $img;
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $this->conn->query("DELETE FROM reply WHERE reply_id = '$id'");
    return mysqli_affected_rows($this->conn);
  }
}