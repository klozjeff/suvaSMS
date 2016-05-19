<?php

class Auth {

  public $user;
  public $db;

    public function getUser($email) {
        $db = $this->db;
        $user = $db->query("SELECT * FROM users WHERE email='".$_SESSION['email']."'");
        if($user->num_rows >= 1) {
          return $user->fetch_array();
        } 
      }

    public function isLogged() {
      if(!empty($_SESSION)) {
        if(isset($_SESSION['auth'])) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    public function isAdmin() {
      if(isset($this->user) && $this->user['is_admin'] == 1) {
        return true;
      } else {
        return false;
      }
    }
    
}

