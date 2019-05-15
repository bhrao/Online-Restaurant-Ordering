<?php 
  header('Content-Type: application/json');
  $db = mysqli_connect('localhost', 'root', 'root', 'wpl_project');
  if (isset($_POST['ph_no_check'])) {
  	$ph_no = trim($_POST['ph_no']);
  	$sql = "SELECT * FROM tbl_login WHERE mobile_number='$ph_no'";
  	$results = mysqli_query($db, $sql);
  	if (mysqli_num_rows($results) > 0) {
  	  $msg= "taken";	
  	}else{
  	  $msg= 'not_taken';
  	}
    echo '{ "msg" : "'. $msg . '"}';
  	exit();
  }
  if (isset($_POST['email_check'])) {
  	$email = trim($_POST['email']);
  	$sql = "SELECT * FROM tbl_login WHERE email_id='$email'";
  	$results = mysqli_query($db, $sql);
  	if (mysqli_num_rows($results) > 0) {
      $msg= "taken";  
    }else{
      $msg= 'not_taken';
    }
    echo '{ "msg" : "'. $msg . '"}';
  	exit();
  }
  if (isset($_POST['save'])) {
    $first_name = mysqli_real_escape_string($db, $_POST['fname']);
    $last_name = mysqli_real_escape_string($db, $_POST['lname']);
  	$ph_no = mysqli_real_escape_string($db, $_POST['ph_no']);
  	$email = mysqli_real_escape_string($db, $_POST['email']);
  	$password = mysqli_real_escape_string($db, password_hash($_POST['password'], PASSWORD_DEFAULT));
  	$query = "INSERT INTO tbl_login (first_name, last_name, email_id, password, mobile_number, is_admin) 
  	       	VALUES ('$first_name', '$last_name', '$email', '$password', '$ph_no', 0)";
  	if(mysqli_query($db, $query)){
      // Redirect to login page
      echo '{ "msg": "success" }';
          
    } else{ 

      echo '{ "msg": "Something went wrong. Please try again later." }';
      
    }
  	
  	
  }