<?php
require_once __DIR__ . '/../config/global.php';

class Session {
  private static $self_instance;
  private $mysqli;
  public $sid;

  public function __construct($dbc){
    $this->mysqli = $dbc;

        //Determines if the user has a session id set
    $this->sid = isset($_SESSION['sid']) ? $_SESSION['sid'] : null;
    if ($this->sid != null) {
      //Sets the current loggedIn status and validates any session in the browser
      $this->validate($this->sid, time());
    }
  }

  public function __destruct() {

  }

  public static function getInstance($dbc){
    if(!self::$self_instance){
      self::$self_instance = new Session($dbc);
    }
    return self::$self_instance;
  }


  //Returns if a session currently exists for a given user
  function sessionExists($userID){
    $qry = $mysqli->prepare("SELECT * from sessions where accountID = ?");
    $qry->bind_param($userID);
    $qry->execute();

    $result = $query->get_result();
    if(count($result) > 0 ){
      return true;
    }
    else{
      return false;
    }
  }

  function validate($sid, $currentTime){


  }

  function login($email, $pass){
    //Validate the credentials of the users
    if($this->validateLogin($email, $pass)){
      //Get the userid
      $userid = $this->getUID($email);
      if($this)
    }
  }

  //Validates the login credentials of the user
  function validateLogin($email, $pass){
    //TODO add password hash functionallity
    $email = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $email));
    $query = $mysqli->prepare("SELECT * FROM account WHERE email = ? && password = ?");
    $query->bind_param($email,$pass);
    $query->execute();

    $users = $query->get_result();
    if(count($users) < 1){
      return false;
    }
    else{
      return true;
    }
  }

  function clearByUID(){

  }

  function getSalt($email){
    $query = $mysqli->prepare("SELECT salt FROM account WHERE email = ?");
    $query->bind_param($email);
    $query->execute();

    $salt = $query->get_result();

    return $salt;
  }

  public function handleSID($userID){
    if($this->exists($userid)){
      if (!$this->clearByUID($userid)) {
        //Couldnt clear the session, return a json element containing the error
        return json_encode("Couldn't clear SID when creating new session.");
      }
    }
    //Creates a session
    if ($this->buildSID($userid)) {
      return true;
    }
    return false;
  }

  //Takes either an email address or a session id and returns a user id
  function getUID($input){
    if(filter_var($input, FILTER_VALIDATE_EMAIL) == true){
      $qry = $mysqli->prepare("SELECT accountID from accounts where email = ?");
      $qry->bind_param($input);
      $qry->execute();

      $result = $qry->get_result();
    }
    else{
      $qry = $mysqli->prepare("SELECT accountID from sessions where sessionID = ?");
      $qry->bind_param($input);
      $qry->execute();

      $result = $qry->get_result();
    }
    return isset($result[0]['userid']) ? $result[0]['userid'] : -1;
  }

  function isLoggedIn() {
    return isset($_SESSION['sid']);
  }

  fuc





}


 ?>
