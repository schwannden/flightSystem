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
$flight  = new flight(  $hostAndDb, $username, $password );
$airport = new airport( $hostAndDb, $username, $password );
if( $_SESSION[is_admin] == true ) {
  echo "<br> <h2> Manage Flight </h2>";
  #admin issued a command
  if( isset( $_POST[command] ) ) {
    switch( $_POST[command] ) {
      case "DELETE_FLIGHT":
        $flight->erase( $_POST[id] );
        break; 
      case "ADD_FLIGHT":
        $flight->add( $_POST[flight_number], $_POST[departure], 
          $_POST[destination]  , $_POST[departure_date], $_POST[arrival_date] );
        break; 
      case "UPDATE_FLIGHT":
        $flight->update( $_POST[id], $_POST[flight_number], $_POST[departure], 
          $_POST[destination], $_POST[departure_date], $_POST[arrival_date] );
        break; 
      case "DELETE_AIRPORT":
        $airport->erase( $_POST[name] );
        break; 
      case "ADD_AIRPORT":
        $airport->add( $_POST[name], $_POST[longitude], $_POST[latitude]);
        break; 
      case "UPDATE_AIRPORT":
        echo "$_POST[name] $_POST[longitude] $_POST[latitude]";
        $airport->update( $_POST[name], $_POST[longitude], $_POST[latitude] );
        break; 
    }
  }
  #a form for admin to add flight record
  echo <<<_HTML
  <pre>
    <form action="flight.php" method="post">
     flight_number: <input type="text" name="flight_number" >
         departure: <input type="text" name="departure" >
       destination: <input type="text" name="destination" >
    departure_date: <input type="text" name="departure_date" >
      arrival_date: <input type="text" name="arrival_date" >
    <button name="command" type="submit" value="ADD_FLIGHT"> ADD FLIGHT </button> </form>
  </pre>
_HTML;
}

$flight->show($_SESSION[is_admin]);

if( $_SESSION[is_admin] ) {
  echo "<br> <h2> Manage Airport </h2>";
  #a form for admin to add airport record
  echo <<<_HTML
  <pre>
    <form action="flight.php" method="post">
          name: <input type="text" name="name"      value="XYZ">
     longitude: <input type="text" name="longitude" value="xx.xxxxx">
      latitude: <input type="text" name="latitude"  value="xx.xxxxx">
    <button name="command" type="submit" value="ADD_AIRPORT"> ADD AIRPORT </button> </form>
  </pre>
_HTML;
  $airport->show();
}

?>
<br>
<a href="home.php" target="_self"> Home </a> <br>
<a href="logout.php" target="_self"> Logout </a> <br>
</body>
</html>

