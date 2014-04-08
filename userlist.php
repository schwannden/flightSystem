<?php
  session_start();
  if( !isset($_SESSION[is_admin]) or $_SESSION[is_admin] != true )
    die();
?>
<!DOCTYPE html>
<html>
<head>
  <title> User List </title>
</head>

<body>
<?php
require_once 'include/login.php';
require_once 'include/lib.php';

$user = new user( $hostAndDb, $username, $password );
if( isset($_POST[command]) )
  if( $_POST[command] == "UPDATE_USER" ) {
    $user->update( $_POST[id], $_POST[is_admin] == "true" );
    echo "The change will take effect the next time this user log in";
  } else if( $_POST[command] == "ADD_USER" ) {
    $add_status = $user->add( $_POST[account], $_POST[password], $_POST[retype_password], $_POST[is_admin]=="true" );
  } else if( $_POST[command] == "DELETE_USER" ) {
    $user->erase( $_POST[id] );
  }
echo <<<_HTML
  <br>
  <h2>  Add New User </h2>
  <pre>
_HTML;
  if( $add_status === true )
    echo "User " . htmlentities( $_POST[account] ) . " is successfully added";
  else
    echo $add_status;
echo <<<_HTML
  <form method='post'>
            Account: <input type="text" name="account", value="$_POST[account]" >
           Password: <input type="password" name="password">
    retype-Password: <input type="password" name="retype_password">
  </pre>
  Make this user administrator?
  <input type="radio" name="is_admin" value=true> YES
  <input type="radio" name="is_admin" value=false checked> NO
  <input type="submit" name="command" value="ADD_USER"> <br>
  </form>
_HTML;

echo" <h2>  User List </h2> ";
$user->show();
?>
<a href="home.php"> Home </a> <br>
<a href="logout.php"> Log Out </a>
</body>
</html>
