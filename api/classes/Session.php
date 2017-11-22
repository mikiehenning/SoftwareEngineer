<?php
require_once __DIR__ . '/../config/global.php';

class Session {
  private static $self_instance;
  private $mysqli; //reference to the database
  public $sid; //session ID

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
  public function registerInstitution($name, $address, $state, $city, $zipCode, $phoneNumber){
    $uid = getUID($this->sid);
    if(getAccountType($uid) == 2){
      $qry = $this->$mysqli->prepare("INSERT INTO institution(name,address,state,city,zipCode,phoneNumber) VALUES(?,?,?,?,?,?)");
      $qry->bind_param("ssssii",$name, $address, $state, $city, $zipCode, $phoneNumber);
      $qry->execute();
    }
    else{
      echo "You are not authorized for this requeset";
    }
  }



  public function registerAccount($email, $password, $isClient, $isAdmin, $institutionID){
    //TODO sanitize inputs; ensure email is an email, ensure password doesn't have weird characters\
    //TODO solve institution picking problem (see TODO 2.2.1.3)


    $salt = random_bytes(32); //create salt for account
    $saltedPassword = $salt.$password;
    $hash = hash('scrypt',$saltedPassword);
    $qry = $this->mysqli->prepare("INSERT INTO account(emailAddress,hash,salt) VALUES(?,?,?)");
    $qry->bind_param("sss",$email,$hash,$salt);
    $qry->execute();
    //Get the institutionID for the insert into clientAccount
    $incrementID = $qry->insert_id;
    //If the created account is a client account create a client account
    if($isClient == 1){
      if($institutionID == -1){
        //If no specified institutionID from the front end get one from the current users institutionID
        $iID = $this->getInstitutionID();
        if($iID != -1){
          $this->registerClientAccount($incrementID,$iID,$isAdmin);
          $qry->close();
          return 1;
        }
        else{
          $qry->close();
          return 0;
        }
      }
      else{
        //Otherwise just use the one from the frontend
        $this->registerClientAccount($incrementID,$institutionID,$isAdmin);
        $qry->close();
        return 1;
      }
    }
    //Otherwise create a system account
    else{
      $this-registerSystemAccount($incrementID);
      $qry->close();
      return 1;
    }
    $qry->close();
    return 0;
  }
  //Returns the institutionID for this user
  function getInstitutionID(){
    $uid = getUID($this->sid);
    $qry = $this->mysqli->prepare("SELECT institutionID from clientAccount where accountID =  ?");
    $qry->bind_param("i",$uid);
    $qry->execute();
    $result = $qry->get_result();
    $qry->close();
    return isset($result[0]['institutionID']) ? $result[0]['institutionID'] : -1;
  }

  function registerSystemAccount($accountID){
    $qry = $this->mysqli->prepare("INSERT INTO systemAdmin VALUES(?)");
    $qry->bind_param("iii",$accountID);
    $qry->execute();
    $qry->close();
  }


  function registerClientAccount($accountID, $institutionID, $isAdmin){
    //Insert into client account
    $qry = $this->mysqli->prepare("INSERT INTO clientAccount VALUES(?,?,?)");
    $qry->bind_param("iii",$accountID,$institutionID,$isAdmin);
    $qry->execute();
    $qry->close();
  }
  public function getAccountType($uid){
    //Select acountID FROM account
    $qry = $this->mysqli->prepare("SELECT accountID from account where accountID =  ?");
    $qry->bind_param("i",$uid);
    $qry->execute();
    //If it exists
    if($qry->num_rows == 1){
      //Pull the accounts is admin
      $qry = $this->mysqli->prepare("SELECT isAdmin from account where accountID =  ?");
      $qry->bind_param("i",$uid);
      $qry->bind_result($isAdmin);
      $qry->execute();
      $qry->store_result();
      //If that exists
      if($qry->num_rows == 1){
        while($qry->fetch()){
          //If is admin the account is a client admin account
          if($isAdmin == 1){
            $qry->free_result();
            $qry->close();
            return 1;
          }
          //Otherwise its a standard client account
          else if($isAdmin == 0){
            $qry->free_result();
            $qry->close();
            return 0;
          }
        }
      }
      //Otherwise the account is a corstrata account
      else{
        $qry->free_result();
        $qry->close();
        return 2;
      }
    }
    //If no account is found return -1 to specify
    $qry->close();
    return -1;

  }
  //TODO implement a change password
  public function changePassword($oldPassword, $newPassword){
    //TODO You need to validate the old password
    if($oldPassword != $newPassword){
      $newSalt = random_bytes(32); //new password, new salt
      $saltedPassword = $newSalt.$newPassword;
      $hash = hash('scrypt',$newPassword);
      $uid = getUID($this->sid);
      $qry = $this->mysqli->prepare("UPDATE account SET hash = ?, salt = ? WHERE accountID = ?")
      $qry->bind_param("ssi",$hash,$saltedPassword,$uid);
      $qry->execute();
      $qry->close();
      return true;
    }
    else {
      return false;
    }
  }

  function validate($sid, $currentTime){
    $sid = htmlentities(mysqli_real_escape_string(this->mysqli),$sid);
    $qry = $this->mysqli->prepare("SELECT timeCreated, accountID FROM sessions WHERE sessionID = ?");
    $qry->bind_param("s",$sid);
    $qry->bind_result($timestamp,$uid);
    $qry->execute();
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
      if($this->handleSID($userid)){
        return 1;

      }
    }
    return 0;
  }

  //Validates the login credentials of the user
  //TODO verify login with hash and salt *NEEDS TESTING*
  function validateLogin($email, $passwordInput){
    //TODO add password hash functionallity *NEEDS TESTING*
    $email = htmlspecialchars(mysqli_real_escape_string($this->mysqli, $email));
    //$qry = $this->$mysqli->prepare("SELECT * FROM account WHERE emailAddress = ? && password = ?");
    //$qry->bind_param("ss",$email,$passwordInput);
    //$qry->execute();
    //$users = $qry->get_result();
    //$qry->close();
    //if(count($users) < 1){
      //return false;
    //}
    //else{
      //return true;
    //}

    $qry = $this->$mysqli->prepare("SELECT salt, hash FROM account WHERE emailAddress = ?")
    $qry->bind_param("s",$email);
    $qry->execute();
    $qry->bind_result($dbSalt,$dbHash);
    $qry->store_result();

    $saltedInput = $dbSalt.$passwordInput;
    $hashedInput = hash('scrypt',$saltedInput);
    if($hashedInput == $dbHash){
      return true; //hashes match, passwords match
    }
    else {
      return false;
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
      if (!$this->clearByUID($userID)) {
        //Couldnt clear the session, return a json element containing the error
        return json_encode("Couldn't clear SID when creating new session.");
      }
    }
    //Creates a session
    if ($this->buildSID($userID)) {
      return true;
    }
    return false;
  }

  //Returns if a session currently exists for a given user
  function sessionExists($userID){
    $qry = $this->mysqli->prepare("SELECT * from sessions where accountID = ?");
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
      $qry = $this->mysqli->prepare("SELECT accountID from accounts where emailAddress = ?");
      $qry->bind_param($input);
      $qry->execute();

      $result = $qry->get_result();
    }
    else{
      $qry = $this->mysqli->prepare("SELECT accountID from sessions where sessionID = ?");
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
    return md5($this->generateRandStr($length);
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

  /*
  TEST FUNCTIONS
   */
  function createTest($patientID){
    $qry = $this->mysqli->prepare("INSERT INTO test (patientID, accountID, dateTaken) VALUES (?, ?, ?)");
    $datetime = date_create()->format('Y-m-d H:i:s');
    $uid = getUID($this->sid);
    $qry->bind_param("iis",$patientID,$uid,$datetime);
    if($qry->execute()){
      return $qry->insert_id;
    }
    return -1;
  }

  public function createWagnerTest($patientID, $grade){
    $testID = $this->createTest($patientID);
    if($testID == -1){
      echo "Test Failed to insert into the database";
    }
    else{
      $qry = $this->mysqli->prepare("INSERT INTO test (testID, grade) VALUES (?, ?)");
      $qry->bind_param("ii",$testID);
    }
  }
  public function createSemmesTest(){

  }
  public function createMonofilimentTest(){

  }
  public function createMiniNutritionalTest(){

  }
  //TODO implement a function that takes all of the data from a pressure wound test and inserts it into the database
  //TODO Call create test to get a test id and insert it into the test table
  public function createPressureWoundTest(){

  }
  //TODO implement a function that takes the information from a pressure wound test and spits out a push score
  function getPUSHScore(){

  }
  //TODO implement a function that takes the information from a pressure wound test and spits out a bates jensen score
  function getBatesJensenScore(){

  }
  //TODO implement a function that takes the information from a pressure wound test and spits out a sussman score
  function getSussmanScore(){

  }
  public function getRecentTests($patientID){

  }
  public function patientSearch($searchInput){

  }
  public function getPatientData($patientID){

  }


}


 ?>
