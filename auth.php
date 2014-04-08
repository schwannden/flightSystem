<?php
session_start();
require_once 'include/login.php';
require_once 'include/lib.php';
$db = setupdb();
try {
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
  <!DOCTYPE html>
  <head>
    <title> logging in </title>
  </head>

  <body>
    <h3> wrong username or password </h3> <br>
    <a href="index.html" target="_self"> Back </a> <br>
  </body>
  </html>
_HTML;
} else {
  $_SESSION[username] = $result->account;
  $_SESSION[is_admin] = $result->is_admin;
  header( "location: home.php" );
}
?>
