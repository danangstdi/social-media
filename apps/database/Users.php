<?php

class Users {
  private $table = 'users';
    public $conn;

    public function __construct($conn) {
      $this->conn = $conn;
    }

  public function register($data) {
    $username =  stripcslashes($data['username']);
    $email =  stripcslashes($data['email']);
    $password = mysqli_real_escape_string($this->conn, $data['password']);

    $result = $this->conn->query("SELECT username FROM $this->table WHERE username = '$username'");
    if (mysqli_fetch_assoc($result)) {
      $_SESSION['username-taken'] = true;
      return false;
    }    

    $emailQuery = $this->conn->query("SELECT email FROM $this->table WHERE email = '$email'");
    if (mysqli_fetch_assoc($emailQuery)) {
      $_SESSION['email-taken'] = true;
      return false;
    }
    
    if( strlen($password) < 7) {
      $_SESSION['password-shorten'] = true;
      return false;
    }
    
    if (preg_match('/[ ]/', $password)) {
      $_SESSION['password-space'] = true;
      return false;
    }

    if (!preg_match('/[0-9]/', $password)) {
      $_SESSION['password-numb'] = true;
      return false;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $this->conn->query("INSERT INTO $this->table (username, email, password, photo, background, theme, level) 
                        VALUES ('$username', '$email', '$hash', 'default-photo.webp', 'default-bg.webp', 'light', 1)");

    return mysqli_affected_rows($this->conn);
  }

  public function login($data) {
    $username =  stripcslashes($data['username']);
    $password = mysqli_real_escape_string($this->conn, $data['password']);

    $result = $this->conn->query("SELECT * FROM $this->table WHERE username = '$username'");
    if (mysqli_num_rows($result) > 0) 
    {
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row['password'])) 
      {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        // $this->conn->query("UPDATE $this->table SET last_login = CURRENT_TIMESTAMP WHERE username = '$username'");
        header('Location: ' . BASE_URL);
        exit;
      }
    }
    $error = false ;

    if( isset($error) ) {
      $_SESSION['not-valid'] = true;
    }
  }

  public function deleteAccount($data) {
    $username =  stripcslashes($data['username']);
    $password = mysqli_real_escape_string($this->conn, $data['password']);

    $result = $this->conn->query("SELECT * FROM $this->table WHERE username = '$username'");
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      if (password_verify($password, $row['password'])) {
        $this->conn->query("DELETE FROM $this->table WHERE username = '$username'");
        header('Location: ' . BASE_URL . '/auth/logout.php');
        exit;
      }
    }
    $error = false ;

    if( isset($error) ) {
      $_SESSION['not-valid'] = true;
    }
  }

  public function Update($sql, $session) {
    $this->conn->query($sql);
    $_SESSION[$session] = true;
    header('Location: ' . BASE_URL . '/profile/?u=' . $_GET['u']);
    return mysqli_affected_rows($this->conn);
  }

  public function updatePhoto($type, $row, $session) {

    $username = $_SESSION['username'];
    $photoName = $_FILES[$type]['name'];
    $photoSize = $_FILES[$type]['size'];
    $tmpName = $_FILES[$type]['tmp_name'];

    $jenisFile = explode('.', $photoName);
    $jenisFile = strtolower(end($jenisFile));

    if ( $photoSize > 3000000) { 
        echo "<script>
                alert('Ukuran file maksimal 3mb');
              </script>";
        return false;
    }

    $namaRandom = $username . '_' . uniqid() . '.' . $jenisFile;
    
    
    move_uploaded_file($tmpName, '../../public/assets/' . $type . '/' . $namaRandom);

    $query = mysqli_query($this->conn, "SELECT " . $row . " FROM " . $this->table . " WHERE username = '$username'");
     $previousCover = mysqli_fetch_assoc($query)[$row];
     if ($previousCover !== 'default-' . $type . '.webp') {
        if ($previousCover && $previousCover !== $namaRandom) {
          $previousCoverPath = '../../public/assets/' . $type . '/' . $previousCover;
          if (file_exists($previousCoverPath)) {
              unlink($previousCoverPath);
          }
        }
     }
      
    $this->conn->query("UPDATE $this->table SET $row = '$namaRandom' WHERE username = '$username'");
    $_SESSION[$session] = true;
    header('Location: ' . BASE_URL . '/profile/?u=' . $username . '&path=' . $previousCoverPath);
    return mysqli_affected_rows($this->conn);
  }
}