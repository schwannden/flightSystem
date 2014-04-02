<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php 
  session_start()
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Flight </title>
<style type="text/css" media="all">
<!--
@import url('http://ncadpostgraduate.com/index.php?css=postgraduate/styles/');
-->
</style>
<style type="text/css" media="print">
<!--
/* It is common to set printer friendly styles such as a white background with black text. */
body {
  background-color: #fff;
  background-image: none;
  border-color: #000; /* Sets the border color properties for an element using shorthand notation */
  color: #000;
}
-->
</style>

<link href="SpryAssets/SpryMenuBarHorizontal1.css" rel="stylesheet" type="text/css" />
</head>

<body> 
<div id="wrapper-3">
  <div id="outerWrapper">
  <div id="contentWrapperhome">
    <div id="col1"><a href="flight.php"><img src="http://sweetclipart.com/multisite/sweetclipart/files/airplane_silhouette_2.png" alt="NCAD Entrance" width="251" height="251" /></a>
<div id="text">
        <h1><a href="flight.php">Manage Flight</a></h1>
</div>
    </div>
    <?php
    if( $_SESSION[is_admin] == true ) {
      echo <<<_HTML
        <div id="col1"><a href="userlist.php"><img src="http://simpleicon.com/wp-content/uploads/user1.png" alt="Prospectus Image" width="251" height="251" />
     </a><div id="text">
      <h1><a href="userlist.php">Manage User</a></h1>
    </div></div>
_HTML;
    }
    ?>
   
    <div id="col1"><a href="http://ncadpostgraduate.com/index.php/yearbook"><img src="http://chineseaca.pic6.eznetonline.com/upload/document_list-512_cFin.png" alt="Graduate Work Image" width="251" height="251" />
      </a><div id="text">
      <h1><a href="http://ncadpostgraduate.com/index.php/yearbook">My Favorite</a></h1>
    </div></div>
  </div>
  </div></div>
  <div id="footer"><a href="logout.php">Log Out</a></div>
  <div id="outerWrapper">
</div>
</div>
</body>
</html>
