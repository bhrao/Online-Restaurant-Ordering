<?php

	//header('Content-Type: application/json');
    if(!isset($_GET['id'])){
        header('Location: home.php');
        exit;
    }
  	include_once "db.php";
    include_once "checkLogin.php";
    if($_SESSION['admin'] != 1){
        header('Location: home.php');
        exit;
    }

  	$res_id = $_GET['id'];
  	$res_query = "SELECT name FROM `tbl_restaurants` WHERE `id` = $res_id";
    $res_results = mysqli_query($db, $res_query);
    if(!$res_results){
        header('Location: home.php');
        exit;
    }
    $row = mysqli_fetch_array($res_results);
    $res_name = $row['name'];

    $cat_sql = "SELECT * FROM tbl_category";

	$cat_results = mysqli_query($db, $cat_sql);

?>


<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="new_item_script.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="_3pYe-" style="height: 130px;">
        <div class="_1Tg1D" id="res_id" value="<?php echo $res_id ?>">Add Item to <?php echo $res_name ?></div>
        <div class="HXZeD"></div>
    <form id="new_item_form">
        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="item_name" name="item_name" class="_381fS">
                <label class="_1Cvlf" for="item_name">
                    Item Name
                    <span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="item_desc" name="item_desc" class="_381fS">
                <label class="_1Cvlf" for="item_desc">Item Description<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="number" step="0.01" id="item_price" name="item_price" class="_381fS">
                <label class="_1Cvlf " for="item_price">Item Price<span></span></label>
            </div>
        </div>

        <div>
            <div data-children-count="1">
            	Category
		        <select id = "category_type" name="category_type">		            
		            <?php while($row = mysqli_fetch_assoc($cat_results)) : ?>
		                <option value="<?php echo $row["id"] ?>">
		                    <?php echo $row['name']?>
		                </option>
		            <?php endwhile; ?>
		        </select>
		    </div>
		</div>

        <div class="_25qBi _2-hTu" id="reg_btn"><a class="a-ayg"><input type="submit"
                                                                        style="display: none;">Add Item</a></div>
    </form>
</div>
</body>
</html>
<!-- scripts -->
