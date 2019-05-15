<?php 
  header('Content-Type: application/json');
  $db = mysqli_connect('localhost', 'root', 'root', 'wpl_project');

  if (isset($_POST['save'])) {
    $item_name = mysqli_real_escape_string($db, $_POST['iname']);
    $item_desc = mysqli_real_escape_string($db, $_POST['idesc']);
  	$item_price = mysqli_real_escape_string($db, $_POST['iprice']);
  	$category_type = mysqli_real_escape_string($db, $_POST['cat_type']);
  	$res_id = mysqli_real_escape_string($db, $_POST['res_id']);    
  	$query = "INSERT INTO tbl_items (name, description, category_id, restaurant_id, price, is_active) 
  	       	VALUES ('$item_name', '$item_desc', '$category_type', '$res_id', '$item_price', 1)";
  	if(mysqli_query($db, $query)){
      // Redirect to login page
      echo '{ "msg": "success" }';
          
    } else{ 

      echo '{ "msg": "Something went wrong. Please try again later." }';
      
    }
  	
  	
  }

?>