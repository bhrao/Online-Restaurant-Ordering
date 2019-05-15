<?php

    
	include_once "db.php";
    include_once "checkLogin.php";
    if($_SESSION['admin'] != 1)
        header("Location: home.php");
	
    if(!isset($_POST['save'])){
        if(!isset($_GET['id'])){
            header('Location: home.php');
            exit;
        }
        else{
            $res_id = $_GET['id'];
            $res_query =    "SELECT r.id, r.name as res_name, a.id as addr_id, a.street, a.unit_no, c.name as city_name, s.name as state_name, 
                    a.zip, r.ph_no, r.type_id, r.rating, r.price_range, r.delivery_time
                    FROM tbl_restaurants r
                    INNER JOIN tbl_address a
                    on a.id = r.address_id                    
                    INNER JOIN tbl_city c
                    on c.id = a.city_id
                    INNER JOIN tbl_state s
                    ON s.id = a.state_id
                    WHERE r.id =  $res_id";

            $res_results = mysqli_query($db, $res_query);
            $row = mysqli_fetch_array($res_results); 
            $res_name = $row['res_name'];
            $res_addr_id = $row['addr_id'];
            $res_street= $row['street'];
            $res_unit_no = $row['unit_no'];
            $res_city = $row['city_name'];
            $res_state = $row['state_name'];
            $res_zip = $row['zip'];
            $res_ph_no = $row['ph_no']; 
            $res_type_id = $row['type_id'];   
            $res_rating = $row['rating'];
            $res_price_range = $row['price_range'];
            $res_delivery_time = $row['delivery_time'];

            $res_type_sql = "SELECT * FROM tbl_restaurant_type";
            $res_type_results = mysqli_query($db, $res_type_sql);
        }

    }
    else {
        $res_name = mysqli_real_escape_string($db, $_POST['r_name']);
        $street = mysqli_real_escape_string($db, $_POST['street']);
        $unit_no = mysqli_real_escape_string($db, $_POST['unit_no']);        
        $zip = mysqli_real_escape_string($db, $_POST['zip']);
        $ph_no = mysqli_real_escape_string($db, $_POST['ph_no']);
        $res_type = mysqli_real_escape_string($db, $_POST['res_type']);
        $price_range = mysqli_real_escape_string($db, $_POST['price_range']);
        $rating = mysqli_real_escape_string($db, $_POST['rating']);
        $delivery_time = mysqli_real_escape_string($db, $_POST['delivery_time']);
        $res_id = mysqli_real_escape_string($db, $_POST['res_id']);

        $res_sql = "SELECT address_id FROM tbl_restaurants WHERE id = $res_id";
        $res_results = mysqli_query($db, $res_sql);
        $res_addr = mysqli_fetch_array($res_results);
        $res_addr_id = $res_addr['address_id'];
        
        $addr_query = "UPDATE `tbl_address` SET street = '$street', unit_no = '$unit_no', zip = $zip WHERE id = $res_addr_id";
        mysqli_query($db, $addr_query);

        $res_query =    "UPDATE tbl_restaurants SET name = '$res_name', type_id =$res_type, ph_no = $ph_no, rating = $rating, 
                        price_range = $price_range, delivery_time = $delivery_time WHERE id = $res_id";

        if(mysqli_query($db, $res_query)){
            // Redirect to login page            
            header("Location: details.php?id=$res_id");
        }

    }

?>

<html>
<head>
    <title>Update Restaurant</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="edit_restaurant_script.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="_3pYe-" style="height: 130px;">
        <div class="_1Tg1D" id="res_id" value="<?php echo $res_id ?>">Update Restaurant - <?php echo $res_name ?></div>
        <div class="HXZeD"></div>
        <!-- <div class="_2r91t">or <a class="_3p4qh" href="index.php">login</a></div>
        <img class="_2tuBw _12_oN jdo4W" width="100" height="105" alt="" style="background-image: url(&quot;&quot;);"
             src="https://res.cloudinary.com/swiggy/image/upload/fl_lossy,f_auto,q_auto/Image-login_btpq7r"></div> -->
    <form id="restaurant_form" action="edit_restaurant.php" method="POST">
        <input type="hidden" name="res_id" value="<?php echo $res_id ?>">
        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="restaurant_name" name="r_name" class="_381fS" value="<?php echo $res_name ?>">
                <label class="_1Cvlf _2tL9P" for="restaurant_name">
                    Restaurant Name
                    <span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="street" name="street" class="_381fS" value="<?php echo $res_street ?>">
                <label class="_1Cvlf _2tL9P" for="street">Street<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="unit_no" name="unit_no" class="_381fS" value="<?php echo $res_unit_no ?>">
                <label class="_1Cvlf _2tL9P" for="unit_no">Unit<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="city" name="city" class="_381fS" value="<?php echo $res_city ?>" readonly>
                <label class="_1Cvlf _2tL9P" for="city">City<span></span></label>
            </div>
        </div>


        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="state" name="state" class="_381fS" value="<?php echo $res_state ?>" readonly>
                <label class="_1Cvlf _2tL9P" for="state">State<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="zip" name="zip" class="_381fS" value="<?php echo $res_zip ?>">
                <label class="_1Cvlf _2tL9P" for="zip">Zip<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="ph_no" name="ph_no" class="_381fS" value="<?php echo $res_ph_no ?>">
                <label class="_1Cvlf _2tL9P" for="ph_no">Phone Number<span></span></label>
            </div>
        </div>

        <div>
            <div>
                Restaurant Type
                <select id = "restaurant_type" name="res_type">
                    <?php while($row = mysqli_fetch_assoc($res_type_results)) : ?>
                        <option value="<?php echo $row["id"] ?>" <?php if($row['id'] == $res_type_id) echo "selected"; ?>>
                            <?php echo $row['type']?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="price_range" name="price_range" class="_381fS" value="<?php echo $res_price_range ?>">
                <label class="_1Cvlf _2tL9P" for="price_range">Price Range<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="rating" name="rating" class="_381fS" value="<?php echo $res_rating ?>">
                <label class="_1Cvlf _2tL9P" for="rating">Rating<span></span></label>
            </div>
        </div>

        <div>
            <div class="_3Um38 _3lG1r" data-children-count="1">
                <input type="text" id="delivery_time" name="delivery_time" class="_381fS" value="<?php echo $res_delivery_time ?>">
                <label class="_1Cvlf _2tL9P" for="delivery_time">Delivery time<span></span></label>
            </div>
        </div>
        <input type="hidden" name="save" value="1" />

        <div class="_25qBi _2-hTu" id="reg_btn"><a class="a-ayg"><input type="submit"
                                                                        style="display: none;">Update Restaurant</a></div>
    </form>
</div>
</body>
</html>
<!-- scripts -->
