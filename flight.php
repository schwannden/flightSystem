<?php
require_once 'include/login.php';
require_once 'include/lib.php';
session_start();
$flight  = new flight(  $hostAndDb, $username, $password );
$airport = new airport(    $hostAndDb, $username, $password );
$user    = new user( $hostAndDb, $username, $password );
if(  $user->exist($_SESSION[username]) ) {
  echo <<<_HTML
    <!DOCTYPE html>
    <html>
    <head>
      <title> "Flights" </title>
    </head>
    <body>
    <h2> Welcome $_SESSION[username] </h2>
_HTML;
} else {
  header("location: index.html");
}

#admin only commands: add, delete, update flight or airport
if( $_SESSION[is_admin] == true ) {
  echo "<br> <h2> Manage Flight </h2>";
  #admin issued a command
  if( isset( $_POST[command] ) ) {
    switch( $_POST[command] ) {
      case "DELETE_FLIGHT":
        $flight->erase( $_POST[id] );
        break; 
      case "ADD_FLIGHT":
        $flight->add( $_POST[flight_number], $_POST[departure], $_POST[destination],
          $_POST[departure_date], $_POST[arrival_date], $_POST[price] );
        break; 
      case "UPDATE_FLIGHT":
        $flight->update( $_POST[id], $_POST[flight_number], $_POST[departure],
          $_POST[destination], $_POST[departure_date], $_POST[arrival_date], $_POST[price] );
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
             price: <input type="text" name="price" >
    <button name="command" type="submit" value="ADD_FLIGHT"> ADD FLIGHT </button> </form>
  </pre>
_HTML;
}
#user issued add to favorite table
if( isset( $_POST[command] ) and $_POST[command] == "ADD_FAVORITE" ) {
  $db = setupdb();
  $query = "SELECT id FROM User Where account=?";
  $sth = $db->prepare( $query );
  $sth->execute( array($_SESSION[username]) );
  $user_id = $sth->fetch()[id];
  $flight_id = $_POST[id];
  $favorite = new favorite( $hostAndDb, $username, $password );
  $favorite->add( $user_id, $flight_id );
}
#user issued a sort comand
if( isset( $_POST[command] ) ) {
  #print_r( $_POST );
  if( $_POST[command] == "CHANGE_ORDER" )
    $flight->show($_SESSION[is_admin], "true", $_POST[ordered_by], $_POST[ordered_how]);
  else if( $_POST[command] == "SEARCH_FLIGHT" )
    $flight->show($_SESSION[is_admin], "$_POST[column_name] = '$_POST[column_value]'");
  else
    $flight->show($_SESSION[is_admin]);
} else
  $flight->show($_SESSION[is_admin]);
#a form for user to issue sort, search command
echo <<<_HTML
    <form action method="post">
      <p>Sorted by</p>
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

    <form action method="post">
      <p>Search for flight mathing:</p> <br>
      <select name="column_name" selected="selected" size="1">
        <option value="flight_number">  Flight Number  </option>
        <option value="departure">      Departure      </option>
        <option value="destination">    Destination    </option>
        <option value="departure_date"> Departure Date </option>
        <option value="arrival_date">   Arrival Date   </option>
        <option value="price">          Price   </option>
      </select>
      =
      <input type="text" name="column_value">
      <button name="command" type="submit" value="SEARCH_FLIGHT"> SEARCH </button>
    </form>
_HTML;
#a form for administrator to edit, add airport
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

