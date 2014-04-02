<!DOCTYPE html>
<?php
  session_start();
  if( !isset($_SESSION[username]) )
    die();
?>
<html>
<head>
  <title> "Flights" </title>
</head>

<body>
<?php
require_once 'include/login.php';
require_once 'include/lib.php';
echo "<h2> Welcome $_SESSION[username] </h2>";
$flight = new flightmod( $hostAndDb, $username, $password );
if( $_SESSION[is_admin] == true ) {
  #admin issued a command, here we don't need to sanitize command, because it has no danger
  if( isset( $_POST[command] ) ) {
    #admin issued delete
    if( $_POST[command] == "DELETE_FLIGHT" ) {
      $flight->erase( $_POST[id] );
    #admin issued add
    } else if( $_POST[command] == "ADD" ) {
      $flight->add( $_POST[flight_number], $_POST[departure],
        $_POST[destination]  , $_POST[departure_date], $_POST[arrival_date] );
    #admin issued edit
    } else if( $_POST[command] == 'UPDATE_FLIGHT' ) {
      $flight->update( $_POST[id], $_POST[flight_number], $_POST[departure], 
        $_POST[destination], $_POST[departure_date], $_POST[arrival_date] );
    }
  }
  #a form for admin to add
  echo <<<_HTML
  <pre>
    <form action="flight.php" method="post">
     flight_number: <input type="text" name="flight_number" >
         departure: <input type="text" name="departure" >
       destination: <input type="text" name="destination" >
    departure_date: <input type="text" name="departure_date" >
      arrival_date: <input type="text" name="arrival_date" >
    <button name="command" type="submit" value="ADD"> ADD RECORD </button> </form>
  </pre>
_HTML;
}
  $flight->show($_SESSION[is_admin]);

?>
<br>
<a href="home.php" target="_self"> Home </a> <br>
<a href="logout.php" target="_self"> Logout </a> <br>
</body>
</html>

