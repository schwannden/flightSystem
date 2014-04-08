<?php
require_once 'login.php';

function sanitize( $string ) {
  if( get_magic_quotes_gpc() ) $string = stripslashes( $string );
  return htmlentities( mysqli_real_escape_string( $string ) );
}

function setupdb() {
  global $hostAndDb, $username, $password;
  try {
    $db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return $db;
  } catch( PDOException $e ) {
    print "Error in setupdb(): " . $e->getMessage() . "<br/>";
    die();
  }
}

class user {
  private $db;
  public function __construct($hostAndDb, $username, $password) {
    try {
      $this->db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
      $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch( PDOException $e ) {
      print "Error in usermod__constructor(): " . $e->getMessage() . "<br/>";
      die();
    }
  }

  public function add( $account, $password, $retype_password, $is_admin ) {
    $validate_status = $this->validate( $account, $password, $retype_password );
    if( $validate_status === true ) {
      try {
        $this->reset_auto_increment();
        $query = "INSERT INTO User (account, password, is_admin) VALUES (?, ?, ?)";
        $sth = $this->db->prepare($query);
        $sth->execute( array($account, md5($password), $is_admin ) );
        return true;
      } catch( PDOException $e ) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
      }
    } else {
      return $validate_status;
    }
  }

  public function erase( $id ) {
    try {
      $query = "DELETE FROM User WHERE id=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($id) );
    } catch( PDOException $e ) {
      print "Error!: " . $e->getMessage() . "<br/>";
    }
  }
  
  public function update( $id, $is_admin ) {
    try {
      $query = "UPDATE User SET is_admin=? WHERE id=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($is_admin, $id) );
    } catch( PDOException $e ) {
      print "Error!: " . $e->getMessage() . "<br/>";
    }
  }

  public function show() {
    #show all records
    $query = "SELECT * FROM User";
    $sth = $this->db->prepare($query);
    $sth->execute();
    $sth->setFetchMode( PDO::FETCH_ASSOC );
    echo "<table border=\"1\">
    <tr> <th> Account </th> <th> Identity </th> <th> Update </th> </tr>";
    while( $row = $sth->fetch() ) {
      $identity = $row[is_admin]? "admin" : "regular user";
      $alternate_identity = $row[is_admin]? "regular user" : "admin";
      $option1 = $row[is_admin]? "true" : "false";
      $option2 = $row[is_admin]? "false" : "true";
      echo <<<_HTML
  <form method="post" >
    <tr>
      <td> $row[account] </td>
      <td>
          <input type="hidden" name="id" value=$row[id]>
          <select name="is_admin" selected="selected" size="1">
            <option value=$option1> $identity </option>
            <option value=$option2> $alternate_identity </option>
          </select>
      </td>
      <td>
         <button name="command" type="submit" value="UPDATE_USER"> Update </button>
         <button name="command" type="submit" value="DELETE_USER"> Delete </button>
      </td>
    </tr>
  </form>
_HTML;
    }
    echo "</table>";
  }

  private function reset_auto_increment() {
    $query = "ALTER TABLE User AUTO_INCREMENT=1";
    $sth = $this->db->prepare($query);
    $sth->execute();
  }

  private function validate( $account, $password, $retype_password ) {
    try {
      $query = "SELECT * FROM User WHERE account=?";
      $sth = $this->db->prepare($query);
      $sth->execute(array($account));
      $sth->setFetchMode( PDO::FETCH_ASSOC );
 
      if( $row = $sth->fetch() ) {
        return "account: $account already exists, choose another account <br>";
      } else if( $password != $retype_password ) {
        return "The two passwords do not match, please type again: <br>";
      } else if($password == false || $account == false) {
        return "password or account can not be empty";
      } else {
        return true;
      }
    } catch( PDOException $e ) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }
}
   
class flight {
  private $db;
  public function __construct($hostAndDb, $username, $password) {
    try {
      $this->db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
      $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch( PDOException $e ) {
      print "Error in flightmod__constructor(): " . $e->getMessage() . "<br/>";
      die();
    }
  }

  public function add($flight_number, $departure, $destination, $departure_date, $arrival_date) {
    try {
      $this->reset_auto_increment();
      $query = "INSERT into Flight 
        (flight_number, departure, destination, departure_date, arrival_date) 
        VALUES (?, ?, ?, ?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($flight_number, $departure, $destination, $departure_date, $arrival_date) );
      return true;
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function erase( $id ) {
    try {
      $query = "DELETE FROM Flight WHERE id=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($id) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
  
  public function update( $id, $flight_number, $departure, $destination, $departure_date, $arrival_date ) {
    try{
      $query = "UPDATE Flight SET 
        flight_number=?, departure=?, destination=?, 
        departure_date=?, arrival_date = ? WHERE id=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($flight_number, $departure, $destination, $departure_date, $arrival_date, $id) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function show( $is_admin, $ordered_by="id", $ordered_how="ASC" ) {
    #show all records
    #var_dump(  $ordered_by );
    #var_dump(  $ordered_how );
    $query = "SELECT * FROM Flight ORDER BY ? ?";
    $sth = $this->db->prepare($query);
    $sth->execute(array($ordered_by, $ordered_how));
    $sth->setFetchMode( PDO::FETCH_ASSOC );
    echo "<table border=\"5\">
      <tr> <th> Flight Number </th> <th> Departure </th>
      <th> Destination </th>        <th> Departure Date </th> 
      <th> Arrival Date </th>       <th> Price </th>";
    if( $is_admin ) {
      echo "<th> Action </th> </tr>";
    }
    
    while( $row = $sth->fetch() ) {
      if( $is_admin == true ) {
        echo <<<_HTML
  <form method="post" >
    <input type="hidden" name="id" value=$row[id]>
    <tr>
      <td> <input type="text" name="flight_number" value=$row[flight_number]> </td>
      <td> <input type="text" name="departure" value=$row[departure]> </td>
      <td> <input type="text" name="destination" value=$row[destination]> </td>
      <td> <input type="text" name="departure_date" value=$row[departure_date]> </td>
      <td> <input type="text" name="arrival_date" value=$row[arrival_date]> </td>
      <td> <input type="text" name="arrival_date" value=$row[price]> </td>
      <td> <button name="command" type="submit" value="UPDATE_FLIGHT"> Update </button>
           <button name="command" type="submit" value="DELETE_FLIGHT"> Delete </button> </td>
    </tr>
  </form>
_HTML;
      } else {
        echo <<<_HTML
  <tr>
    <td> $row[flight_number] </td> <td> $row[departure] </td>
    <td> $row[destination] </td>   <td> $row[departure_date] </td>
    <td> $row[arrival_date] </td>  <td> $row[price] </td>
  </tr>
_HTML;
      }
    }
    echo "</table>";
  }

  public function reset_auto_increment() {
    $query = "ALTER TABLE Flight AUTO_INCREMENT=1";
    $sth = $this->db->prepare($query);
    $sth->execute();
  }
}


class airport {
  private $db;
  public function __construct($hostAndDb, $username, $password) {
    try {
      $this->db = new PDO( $hostAndDb, $username, $password, array(PDO::ATTR_PERSISTENT => true) );
      $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch( PDOException $e ) {
      print "Error in airport__constructor(): " . $e->getMessage() . "<br/>";
      die();
    }
  }

  public function add($name, $longitude, $latitude) {
    try {
      $query = "INSERT into Airport VALUES (?, ?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($name, $longitude, $latitude) );
      return true;
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function erase( $name ) {
    try {
      $query = "DELETE FROM Airport WHERE name=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($name) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
  
  public function update($name, $longitude, $latitude) {
    try{
      $query = "UPDATE Airport SET 
        longitude=?, latitude=? 
        WHERE name=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($longitude, $latitude, $name) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function show() {
    #show all records
    $query = "SELECT * FROM Airport";
    $sth = $this->db->prepare($query);
    $sth->execute();
    $sth->setFetchMode( PDO::FETCH_ASSOC );
    echo "<table border=\"5\">
      <tr> <th> Airport Name </th>  <th> Position </th> <th> Action </th> </tr>";

    while( $row = $sth->fetch() ) {
      echo <<<_HTML
  <form method="post" >
           <input type="hidden" name="name" value=$row[name]>
    <tr>
      <td> $row[name] </td>
      <td> <input type="text" name="longitude"  value=$row[longitude]>                  </td>
      <td> <input type="text" name="latitude"   value=$row[latitude]>                   </td>
      <td> <button name="command" type="submit" value="UPDATE_AIRPORT"> Update </button>
           <button name="command" type="submit" value="DELETE_AIRPORT"> Delete </button> </td>
    </tr>
  </form>
_HTML;
    }
    echo "</table>";
  }
}
