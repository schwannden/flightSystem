<?php
require_once 'include/login.php';
require_once 'include/lib.php';
session_start();
$user     = new user( $hostAndDb, $username, $password );
$favorite = new favorite( $hostAndDb, $username, $password );
if(  $user->exist($_SESSION[username]) ) {
  echo <<<_HTML
    <!DOCTYPE html>
    <html>
    <head>
      <title> "Favorite" </title>
    </head>
    <body>
    <h2> Welcome $_SESSION[username] </h2>
_HTML;
} else {
  header("location: index.html");
}
$db = setupdb();
$query = "SELECT id FROM User Where account=?";
$sth = $db->prepare( $query );
$sth->execute( array($_SESSION[username]) );
$user_id = $sth->fetch()[id];

#sorted by comand
echo <<<_HTML
  <pre>
    Sorted by <form action method="post">
      <select name="ordered_by" selected="selected" size="1">
        <option value="flight_number">  Flight Number  </option>
        <option value="departure">      Departure      </option>
        <option value="destination">    Destination    </option>
        <option value="departure_date"> Departure Date </option>
        <option value="arrival_date">   Arrival Date   </option>
        <option value="price">          Price   </option>
      </select> <select name="ordered_how" selected="selected" size="1">
        <option value="ASC">  Increasing </option>
        <option value="DESC"> Decreasing </option>
      </select> <button name="command" type="submit" value="CHANGE_ORDER"> APPLY </button>
    </form>
  </pre>
_HTML;

if( isset( $_POST[command] ) ) {
  switch( $_POST[command] ) {
    case "CHANGE_ORDER":
      $favorite->show($user_id, $_POST[ordered_by], $_POST[ordered_how]);
      break;
    case "DELETE_FAVORITE":
      $favorite->erase( $user_id, $_POST[flight_id] );
      $favorite->show($user_id);
      break;
    default:
      $favorite->show($user_id);
      break;
  }
} else {
  $favorite->show($user_id);
}

?>

<br>
<a href="home.php" target="_self"> Home </a> <br>
<a href="logout.php" target="_self"> Logout </a> <br>
</body>
</html>
