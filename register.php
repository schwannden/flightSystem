<!DOCTYPE html>
<html>
<head>
  <title> registration </title>
</head>

<body>
<h2> Registration Page </h2>
<?php
require_once 'include/login.php';
if( isset($_POST[confirm_register]) ) {
  try {
    $hostAndDb = "mysql:host=$host;dbname=$database";
    $db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  } catch( PDOException $e ) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }

  $validate_status = validate( $db, $_POST[account], $_POST[passwd], $_POST[retype_passwd] );
  if( $validate_status === true ) {
    try {
      #reset auto increment to the highted id of User table
      $query = "ALTER TABLE User AUTO_INCREMENT=1";
      $sth = $db->prepare($query);
      $sth->execute();
      #insert user
      $query = "INSERT INTO User (account, password, is_admin) VALUES (?, ?, ?)";
      $sth = $db->prepare($query);
      $sth->execute( array($_POST[account], md5($_POST[passwd]), $_POST[is_admin] == 'true' ) );
      echo "Register Successful, now log in from home";
      echo <<<_HTML
        <a href="index.php" target="_self"> Back </a> <br>
_HTML;
    } catch( PDOException $e ) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  } else {
    echo "$validate_status <br>";
    echo <<<_HTML
      <a href="register.php" target="_self"> Back </a> <br>
_HTML;
  }
} else {
  echo <<<_HTML
  <pre>
  <form action='register.php', method='post'>
            Account: <input type="text" name="account", value="$_POST[account]" >
           Password: <input type="password" name="passwd">
    retype-Password: <input type="password" name="retype_passwd">
  </pre>
  Make this user administrator?
  <input type="hidden" name="confirm_register" value="yes">
  <input type="radio" name="is_admin" value=true> YES
  <input type="radio" name="is_admin" value=false> NO
  <input type="submit" value="Confirm"> <br>
  </form>
_HTML;
}

function validate( &$db, $account, $passwd, $retype_passwd ) {
  try {
    $query = "SELECT * FROM User WHERE account=?";
    $sth = $db->prepare($query);
    $sth->execute(array($account));
    $sth->setFetchMode( PDO::FETCH_ASSOC );

    if( $row = $sth->fetch() ) {
      return "account: $account already exists, choose another account <br>";
    } else if( $passwd != $retype_passwd ) {
      return "The two passwords do not match, please type again: <br>";
    } else if($passwd == false || $account == false) {
      return "password or account can not be empty";
    } else {
      return true;
    }
  } catch( PDOException $e ) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}

?>
<a alt="home" href="index.html"> Home </a>
</body>
</html>
