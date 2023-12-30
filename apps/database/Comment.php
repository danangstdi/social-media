<?php

class Comment {
  private $table = 'comment';
  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function createComment($data) {
    $comment = htmlspecialchars($data['comment']);
    $id = $_GET['s'];
    $username = $_SESSION['username'];
    $comment_id = 'comment_' . $username . '_' . uniqid();

    $photoName = $_FILES['comment-photo']['name'];
    $photoSize = $_FILES['comment-photo']['size'];
    $error = $_FILES['comment-photo']['error'];
    $tmpName = $_FILES['comment-photo']['tmp_name'];

    $jenisFile = explode('.', $photoName);
    $jenisFile = strtolower(end($jenisFile));

    if ( empty($comment) && empty($photoName) ) {
        echo "<script>
                alert('Anda tidak mengirimkan apapun');
            </script>";
        return false;
    }

    //mencegah user memilih file foto dengan size lebih dari 3mb
    if ( $photoSize > 3000000) { 
        echo "<script>
                alert('Ukuran file maksimal 3mb');
              </script>";
        return false;
    }

    if( $error !== 4 ) {
      $namaRandom = 'comment' . $username . '_' . uniqid() . '.' . $jenisFile;
      move_uploaded_file($tmpName, '../public/assets/comment-image/' . $namaRandom);
    } else {
      $namaRandom = NULL;
    }

    $this->conn->query("INSERT INTO $this->table VALUES ('$comment_id' , '$id', '$username', '$comment', '$namaRandom' , CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
    header('Location:' . BASE_URL . '/story/?u=' . $_GET['u'] . '&s=' . $id);
    return mysqli_affected_rows($this->conn);
  }

  public function deleteComment($data) {
    $id = $data['comment_id'];
    $img = $data['comment_image'];

    $query = $this->conn->query("SELECT * FROM reply WHERE comment_id = '$id'");
    $previousCover = mysqli_fetch_assoc($query)['reply_image'];
    $imagePath = '../public/assets/comment-image/' . $img;
    $imageReply = '../public/assets/comment-image/' . $previousCover;
    if (file_exists($imagePath)) {
        unlink($imagePath);
        unlink($imageReply);
    }

    $this->conn->query("DELETE FROM comment WHERE comment_id = '$id'");
    return mysqli_affected_rows($this->conn);
  }
}