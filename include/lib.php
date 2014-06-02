<?php
require_once 'login.php';
require_once 'find_airline.php';

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
      if( $row[is_admin] == true ) {
        $option = "<option value=\"true\"> admin </option>";
      } else {
        $option = "<option value=\"false\"> regular user </option>
                   <option value=\"true\"> admin </option>";
      }
      echo <<<_HTML
  <form method="post" >
    <tr>
      <td> $row[account] </td>
      <td>
          <input type="hidden" name="id" value=$row[id]>
          <select name="is_admin" selected="selected" size="1">
            $option
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

  public function exist( $username ) {
    try {
      $query = "SELECT * FROM User WHERE account=?";
      $sth = $this->db->prepare($query);
      $sth->execute(array($username));
      $sth->setFetchMode( PDO::FETCH_ASSOC );
 
      if( $row = $sth->fetch() ) {  
        return true;
      } else {
        return false;
      }
    } catch( PDOException $e ) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    return true;
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

  public function add($flight_number, $departure, $destination, $departure_date, $arrival_date, $price) {
    try {
      $this->reset_auto_increment();
      $query = "INSERT into Flight 
        (flight_number, departure, destination, departure_date, arrival_date, price) 
        VALUES (?, ?, ?, ?, ?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($flight_number, $departure, $destination, $departure_date, $arrival_date, $price) );
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
  
  public function update( $id, $flight_number, $departure, $destination, $departure_date, $arrival_date, $price ) {
    try{
      $query = "UPDATE Flight SET 
        flight_number=?, departure=?, destination=?, 
        departure_date=?, arrival_date=?, price=? WHERE id=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($flight_number, $departure, $destination, $departure_date, $arrival_date, $price, $id) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function show( $is_admin, $pred=true, $ordered_by="id", $ordered_how="ASC" ) {
    $query = "SELECT * FROM Flight WHERE $pred ORDER BY $ordered_by $ordered_how";
    $sth = $this->db->prepare($query);
    $sth->execute();
    echo "<table id=\"flight_table\" border=\"5\">
      <tr> <th> Flight Number </th> <th> Departure </th>
      <th> Destination </th>        <th> Departure Date </th> 
      <th> Arrival Date </th>       <th> Price </th>
      <th> Action </th> </tr>";
    
    while( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
      if( $is_admin == true ) {
        echo <<<_HTML
  <form method="post" >
    <input type="hidden" name="id" value=$row[id]>
    <tr>
      <td> <input type="text" name="flight_number" value="$row[flight_number]"> </td>
      <td> <input type="text" name="departure" value="$row[departure]"> </td>
      <td> <input type="text" name="destination" value="$row[destination]"> </td>
      <td> <input type="text" name="departure_date" value="$row[departure_date]"> </td>
      <td> <input type="text" name="arrival_date" value="$row[arrival_date]"> </td>
      <td> <input type="text" name="price" value="$row[price]"> </td>
      <td> <button name="command" type="submit" value="UPDATE_FLIGHT"> Update </button>
           <button name="command" type="submit" value="ADD_FAVORITE"> ADD TO FAVORITE </button>
           <button name="command" type="submit" value="DELETE_FLIGHT"> Delete </button> </td> </tr>
  </form>
_HTML;
      } else {
        echo <<<_HTML
  <tr>
    <td> $row[flight_number] </td> <td> $row[departure] </td>
    <td> $row[destination] </td>   <td> $row[departure_date] </td>
    <td> $row[arrival_date] </td>  <td> $row[price] </td>
    <form method="post" >
    <input type="hidden" name="id" value=$row[id]>
    <td> <button name="command" type="submit" value="ADD_FAVORITE"> ADD TO FAVORITE </button> </td>
    </form>
  </tr>
_HTML;
      }
    }
    echo "</table>";
  }

  public function show_airline( $destination, $departure, $maximum_transition, $ordered_by, $ordered_how ) {
    try{
      $query = find_airline( $destination, $departure, $ordered_by, $ordered_how );
      $sth = $this->db->prepare($query);
      $sth->execute();
      echo <<<_HTML
        <table id="airline_table" border="5">
        <tr> <th> Total Transition </th> <th> Flight(s) </th>
        <th> Departure Date
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="departure_date">
            <input type="hidden" name="ordered_how" value="DESC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> v </button>
          </form>
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="departure_date">
            <input type="hidden" name="ordered_how" value="ASC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> ^ </button>
          </form>
        </th>
        <th> Arrival Date
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="arrival_date">
            <input type="hidden" name="ordered_how" value="DESC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> v </button>
          </form>
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="arrival_date">
            <input type="hidden" name="ordered_how" value="ASC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> ^ </button>
          </form>
        </th> 
        <th> Total Flight Time </th>     <th> Total Transition Time </th> 
        <th> Price
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="price">
            <input type="hidden" name="ordered_how" value="DESC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> v </button>
          </form>
          <form method="post" >
            <input type="hidden" name="maximum_transition" value=$_POST[maximum_transition]>
            <input type="hidden" name="departure"   value=$_POST[departure]>
            <input type="hidden" name="destination" value=$_POST[destination]>
            <input type="hidden" name="ordered_by"  value="price">
            <input type="hidden" name="ordered_how" value="ASC">
            <button name="command" type="submit" value="SEARCH_AIRLINE"> ^ </button>
          </form>
        </th>
_HTML;
      while( ($row = $sth->fetch( PDO::FETCH_ASSOC )) ) {
          if( $row[transition] <= $maximum_transition )
          {
          $flights = $row[first_flight] . "( $row[s1] -> $row[s2] )";
          if( $row[first_flight] != $row[second_flight] )
            $flights = $flights . "<br>" . $row[second_flight] . "( $row[s2] -> $row[s3] )";
          if( $row[second_flight] != $row[third_flight] )
            $flights = $flights . "<br>" . $row[third_flight] . "( $row[s3] -> $row[s4] )";
        echo <<<_HTML
    <tr>
      <td> $row[transition] </td>  <td> $flights </td>
      <td> $row[departure_date] </td>  <td> $row[arrival_date] </td>
      <td> $row[flight_time] </td> <td> $row[transition_time] </td>
      <td> $row[price] </td>
    </tr>
_HTML;
        }
      }
      echo "</table>";
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function reset_auto_increment() {
    $query = "ALTER TABLE Flight AUTO_INCREMENT=1";
    $sth = $this->db->prepare($query);
    $sth->execute();
  }
}

class favorite{
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

  public function add($uid, $fid) {
    try {
      $query = "INSERT into Favorite VALUES (?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($uid, $fid) );
      return true;
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function erase( $uid, $fid ) {
    try {
      $query = "DELETE FROM Favorite WHERE user_id=$uid AND flight_id=$fid";
      $sth = $this->db->prepare($query);
      $sth->execute( array($uid, $fid) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
  
  public function show( $uid, $ordered_by="id", $ordered_how="ASC" ) {
    #show all records
    $query = "SELECT * FROM Flight WHERE id IN
      (SELECT flight_id as id FROM Favorite WHERE user_id = ? )
      ORDER BY $ordered_by $ordered_how";
    $sth = $this->db->prepare($query);
    $sth->execute( array($uid) );
    echo "<table border=\"5\">
      <tr> <th> Flight Number </th> <th> Departure </th>
      <th> Destination </th>        <th> Departure Date </th> 
      <th> Arrival Date </th>       <th> Price </th>
      <th> Action </th> </tr>";
    
    while( $row = $sth->fetch( PDO::FETCH_ASSOC ) ) {
      echo <<<_HTML
  <form method="post" >
    <input type="hidden" name="flight_id" value=$row[id]>
    <tr>
      <td> $row[flight_number] </td> <td> $row[departure] </td>
      <td> $row[destination] </td>   <td> $row[departure_date] </td>
      <td> $row[arrival_date] </td>  <td> $row[price] </td>
      <td> <button name="command" type="submit" value="DELETE_FAVORITE"> Delete </button> </td>
    </tr>
  </form>
_HTML;
    }
    echo "</table>";
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

  public function add($code, $name, $country, $longitude, $latitude) {
    try {
      $query = "INSERT into Airport VALUES (?, ?, ?, ?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($code, $name, $country, $longitude, $latitude) );
      return true;
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function erase( $code ) {
    try {
      $query = "DELETE FROM Airport WHERE code=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($code) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
  
  public function update($code, $longitude, $latitude) {
    try{
      $query = "UPDATE Airport SET 
        longitude=?, latitude=? 
        WHERE code=?";
      echo "UPDATE Airport SET longitude=$longitude latitude=$latitude  WHERE code=$code";
      $sth = $this->db->prepare($query);
      $sth->execute( array($longitude, $latitude, $code) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function show() {
    #show all records
    $query = "SELECT
      Airport.code, Airport.name as aname, country, longitude, latitude, Country.name as cname
      FROM Airport, Country where Airport.country = Country.code";
    $sth = $this->db->prepare($query);
    $sth->execute();
    $sth->setFetchMode( PDO::FETCH_ASSOC );
    echo "<table border=\"5\">
      <tr> <th> Airport </th> <th> IATA Code </th> <th> Country </th> <th> longitude </th> <th> latitude </th> <th> Action </th> </tr>";

    while( $row = $sth->fetch() ) {
      //print_r( $row );
      echo <<<_HTML
  <form method="post" >
           <input type="hidden" name="code" value=$row[code]>
    <tr>
      <td> $row[aname] </td>
      <td> $row[code] </td>
      <td> $row[cname] </td>
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

class country {
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

  public function add($code, $name, $timezone) {
    try {
      #echo "code: $code, name: $name, timezone: $timezone";
      $query = "INSERT into Country VALUES (?, ?, ?)";
      $sth = $this->db->prepare($query);
      $sth->execute( array($code, $name, $timezone) );
      return true;
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function erase( $code ) {
    try {
      $query = "DELETE FROM Country WHERE code=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($code) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
  
  public function update($code_old, $code_new, $name, $timezone) {
    try{
      $query = "UPDATE Country SET 
        code=?, name=?, timezone=?
        WHERE code=?";
      $sth = $this->db->prepare($query);
      $sth->execute( array($code_new, $name, $timezone, $code_old) );
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }

  public function show() {
    try{
      #show all records
      $query = "SELECT * FROM Country";
      $sth = $this->db->prepare($query);
      $sth->execute();
      $sth->setFetchMode( PDO::FETCH_ASSOC );
      echo "<table border=\"5\">
        <tr> <th> Country Code </th> <th> Country name </th> <th> timezone </th> <th> action </th>";
 
      while( $row = $sth->fetch() ) {
        echo <<<_HTML
    <form method="post" >
      <input type="hidden" name="code_old"        value=$row[code]>     </td>
      <tr>
        <td> <input type="text" name="code"       value=$row[code]>     </td>
        <td> <input type="text" name="name"       value="$row[name]">   </td>
        <td> <input type="text" name="timezone"   value=$row[timezone]> </td>
        <td> <button name="command" type="submit" value="UPDATE_COUNTRY"> Update </button>
             <button name="command" type="submit" value="DELETE_COUNTRY"> Delete </button> </td>
      </tr>
    </form>
_HTML;
      }
      echo "</table>";
    } catch( PDOException $e ) {
      print "<h3> Error!: " . $e->getMessage() . "<br/> </h3>";
    }
  }
}
?>
