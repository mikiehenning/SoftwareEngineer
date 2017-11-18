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

  //TODO implement a function to register institutions
  public function registerInstitution()[

  ]
  //TODO implement a function to register accounts
  public function registerAccount(){

  }
  //TODO implement a change password
  public function changePassword($oldPassword, $newPassword, $newPasswordConf){

  }
  

  function validate($sid, $currentTime){
    $sid = htmlentities(mysqli_real_escape_string(this->mysqli),$sid);
    $qry = $this->mysqli->prepare("SELECT timeCreated, accountID FROM 'sessions' WHERE 'sid' = ?");
    $qry->bind_param("s",$sid);
    $qry->bind_result($timestamp,$uid);
    $qry->execute()
    $qry->store_result();
    if($qry->num_rows >=1){
      while($qry->fetch()){
        if($currentTime > $timestamp){
          $this->clear($sid);
          return false;
        }
        else{
          return true;
        }
      }
    }
    else{
      if(isset($_SESSION['sid'])){
        $this->clear($sid);
      }
    }
    $qry->close();
  }

  //Logs in with an email and password if successful creates a session
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
    $qry = $this->$mysqli->prepare("SELECT * FROM account WHERE emailAddress = ? && password = ?");
    $qry->bind_param("ss",$email,$pass);
    $qry->execute();

    $users = $qry->get_result();
    $qry->close();
    if(count($users) < 1){
      return false;
    }
    else{
      return true;
    }
  }
  //Gets the salt based off of an email
  function getSalt($email){
    $qry = $this->$mysqli->prepare("SELECT salt FROM account WHERE emailAddress = ?");
    $qry->bind_param("s",$email);
    $qry->execute();

    $salt = $query->get_result();
    $qry->close();
    return return isset($result[0]['salt']) ? $result[0]['salt'] : -1;
  }


  //Prevents more than one session per user
  public function handleSID($userID){
    if($this->sessionExists($userID)){
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

  //Returns if a session currently exists for a given user
  function sessionExists($userID){
    $qry = $this->$mysqli->prepare("SELECT * from sessions where accountID = ?");
    $qry->bind_param("i", $userID);
    $qry->execute();

    $result = $qry->get_result();
    $qry->close();
    if(count($result) > 0 ){
      return true;
    }
    else{
      return false;
    }
  }

  //Clears the sessions any sessions where the account id is in use
  function clearByUID($userID){
    if ($this->mysqli->query("DELETE FROM sessions WHERE accountID='{$userID}'")) {
      return true;
    }
    else {
      return $this->mysqli->error;
    }
    unset($_SESSION['sid']);
  }

  //Builds a session ID for the current session
  function buildSID($userid) {
    $sid = $this->generateRandID(16);
    $time = time();
    $timestamp = $time + 60 * SESSION_LENGTH;

    $qry = $this->$mysqli->prepare("INSERT INTO sessions (sessionID, accountID, timeCreated) VALUES (?, ?, ?)");
    $qry->bind_param("iii",$sid,$userid,$timestamp);

    if ($qry->execute()) {
      $_SESSION['sid'] = $sid;
      $qry->close();
      return 1;
    }
    return 0;
  }

  //Takes either an email address or a session id and returns a user id
  function getUID($input){
    if(filter_var($input, FILTER_VALIDATE_EMAIL) == true){
      $qry = $this->$mysqli->prepare("SELECT accountID from accounts where emailAddress = ?");
      $qry->bind_param($input);
      $qry->execute();

      $result = $qry->get_result();
    }
    else{
      $qry = $this->$mysqli->prepare("SELECT accountID from sessions where sessionID = ?");
      $qry->bind_param($input);
      $qry->execute();

      $result = $qry->get_result();
    }
    $qry->close();
    return isset($result[0]['userid']) ? $result[0]['userid'] : -1;
  }

  //Verifies if the session is logged in
  function isLoggedIn() {
    return isset($_SESSION['sid']);
  }

  //Generates a random ID with a specified length
  function generateRandID($length) {
    return md5($this->generateRandStr($length)
  }

  //Generates a random string with a length
  function generateRandStr($length) {
    $randstr = "";
    for ($i = 0; $i < $length; $i++) {
      $randnum = mt_rand(0, 61);
      if ($randnum < 10) {
        $randstr .= chr($randnum + 48);
      } elseif ($randnum < 36) {
        $randstr .= chr($randnum + 55);
      } else {
        $randstr .= chr($randnum + 61);
      }
    }
    return $randstr;
  }

}


 ?>
