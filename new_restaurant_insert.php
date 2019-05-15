<?php 
  header('Content-Type: application/json');
  $db = mysqli_connect('localhost', 'root', 'root', 'wpl_project');
  if (isset($_POST['ph_no_check'])) {
  	$ph_no = trim($_POST['ph_no']);
  	$sql = "SELECT * FROM `tbl_restaurants` WHERE ph_no='$ph_no'";
  	$results = mysqli_query($db, $sql);
  	if (mysqli_num_rows($results) > 0) {
  	  $msg= "taken";	
  	}else{
  	  $msg= 'not_taken';
  	}
    echo '{ "msg" : "'. $msg . '"}';
  	exit();
  }
  


?>