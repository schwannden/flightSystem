<!DOCTYPE html>
<html>
<head>
  <title> registration </title>
</head>

<body>
<h2> Registration Page </h2>
<?php
require_once 'include/login.php';
require_once 'include/lib.php';
$user = new usermod( $hostAndDb, $username, $password );
if( isset($_POST[submit]) ) {
  $useradd_status = $user->add( $_POST[account], $_POST[password], $_POST[retype_password], false );
  if( $useradd_status === true ) {
    echo "Register Successful, now log in from";
  } else {
    echo "$useradd_status <br> go ";
    echo <<<_HTML
      <a href="register.php" target="_self"> Back </a> <br>
      go 
_HTML;
  }
} else {
  echo <<<_HTML
  <pre>
  <form action='register.php', method='post'>
            Account: <input type="text" name="account", value="$_POST[account]" >
           Password: <input type="password" name="password">
    retype-Password: <input type="password" name="retype_password">
  </pre>
  <input type="submit" name="submit" value="yes"> <br>
  </form>
_HTML;
}

?>
<a alt="home" href="index.html"> Home </a>
</body>
</html>
