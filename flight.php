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
echo "<h2> Welcome $_SESSION[username] </h2>";
try {
  $db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  if( $_SESSION[is_admin] == true ) {
    #admin issued a command, here we don't need to sanitize command, because it has no danger
    if( isset( $_POST[command] ) ) {
      #admin issued delete
      if( $_POST[command] == "DELETE" ) {
        $query = "DELETE FROM Flight WHERE id=?";
        $sth = $db->prepare($query);
        $sth->execute( array($_POST[id]) );
      #admin issued add
      } else if( $_POST[command] == "ADD" ) {
        #reset auto increment to the highted id of User table
        $query = "ALTER TABLE User AUTO_INCREMENT=1";
        $sth = $db->prepare($query);
        $sth->execute();
        #add flight record
        $query = "INSERT into Flight 
          (flight_number, departure, destination, departure_date, arrival_date) 
          VALUES (?, ?, ?, ?, ?)";
        $sth = $db->prepare($query);
        $sth->execute( array( $_POST[flight_number], $_POST[departure], 
          $_POST[destination]  , $_POST[departure_date], $_POST[arrival_date] ) );
      #admin issued edit
      } else if( $_POST[command] == 'EDIT' ) {
        $query = "UPDATE Flight SET 
          flight_number=?, departure=?, destination=?, 
          departure_date=?, arrival_date = ? WHERE id=?";
        $sth = $db->prepare($query);
        $sth->execute( array( $_POST[flight_number], $_POST[departure], 
          $_POST[destination], $_POST[departure_date], $_POST[arrival_date], $_POST[id] ) );
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

  #show all records
  $query = "SELECT * FROM Flight";
  $sth = $db->prepare($query);
  $sth->execute();
  $sth->setFetchMode( PDO::FETCH_ASSOC );
  while( $row = $sth->fetch() ) {
    if( $_SESSION[is_admin] == true ) {
      #show admin's editable form
      echo <<<_HTML
  <pre>
  <form action="flight.php" method="post">
                  <input type="hidden" name="id"           value=$row[id]>
   flight_number: <input type="text" name="flight_number"  value=$row[flight_number] >
       departure: <input type="text" name="departure"      value=$row[departure] >
     destination: <input type="text" name="destination"    value=$row[destination] >
  departure_date: <input type="text" name="departure_date" value=$row[departure_date] >
    arrival_date: <input type="text" name="arrival_date"   value=$row[arrival_date] >
  </pre>
      <button name="command" type="submit" value="EDIT"> EDIT </button>
      <button name="command" type="submit" value="DELETE"> DELETE </button> </form>
_HTML;
    } else {
      #show normal user's form
      echo <<<_HTML
  <pre>
       flight_number: $row[flight_number]
           departure: $row[departure]
         destination: $row[destination]
      departure_date: $row[departure_date]
        arrival_date: $row[arrival_date]
  </pre> <br>
_HTML;
    }
  }
} catch( PDOException $e ) {
  print "Error!: " . $e->getMessage() . "<br/>";
  session_destroy();
  die();
}

?>
<br>
<a href="logout.php" target="_self"> Logout </a> <br>
</body>
</html>

