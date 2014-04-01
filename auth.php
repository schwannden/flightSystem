<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
  <title> logging in </title>
</head>

<body>
<?php
require_once 'include/login.php';
$hostAndDb = "mysql:host=$host;dbname=$database";
try {
  $db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $query = "SELECT * FROM User WHERE account = ? AND password = ?";
  $sth = $db->prepare($query);
  $sth->execute( array($_POST[account], md5($_POST[passwd])) );
  $result = $sth->fetchObject();
} catch( PDOException $e ) {
  print "Error!: " . $e->getMessage() . "<br/>";
  session_destroy();
  die();
}

if( $result == false ) {
  session_destroy();
  echo <<<_HTML
    <h3> wrong username or password </h3> <br>
    <a href="index.html" target="_self"> Back </a> <br>
_HTML;
} else {
  $_SESSION[username] = $_POST[account];
  $_SESSION[is_admin] = $result->is_admin;
  echo session_id();
  header( "location: flight.php" );
}

?>
</body>
</html>
