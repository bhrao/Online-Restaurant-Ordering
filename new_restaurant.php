<?php
include_once "db.php";
include_once "checkLogin.php";
if ($_SESSION['admin'] != 1)
    header("Location: home.php");

$res_sql = "SELECT * FROM tbl_restaurant_type";

$res_results = mysqli_query($db, $res_sql);

if (isset($_POST['save'])) {
    $res_name = mysqli_real_escape_string($db, $_POST['r_name']);
    $street = mysqli_real_escape_string($db, $_POST['street']);
    $unit_no = mysqli_real_escape_string($db, $_POST['unit_no']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $state = mysqli_real_escape_string($db, $_POST['state']);
    $zip = mysqli_real_escape_string($db, $_POST['zip']);
    $ph_no = mysqli_real_escape_string($db, $_POST['ph_no']);
    $res_type = mysqli_real_escape_string($db, $_POST['res_type']);
    $price_range = mysqli_real_escape_string($db, $_POST['price_range']);
    $rating = mysqli_real_escape_string($db, $_POST['rating']);
    $delivery_time = mysqli_real_escape_string($db, $_POST['delivery_time']);
    if (!isset($_FILES['image'])) {
        echo "no image provided";
        exit;
    }

    $city_query = "SELECT id FROM `tbl_city` WHERE `name` = '$city'";
    $city_results = mysqli_query($db, $city_query);
    if (mysqli_num_rows($city_results) > 0) {
        $row = mysqli_fetch_array($city_results);
        $city = $row['id'];
    } else {
        $city_query = "INSERT INTO `tbl_city` (name) VALUES ('$city')";
        mysqli_query($db, $city_query);
        $city_query = "SELECT id FROM `tbl_city` WHERE `name` = '$city'";
        $city_results = mysqli_query($db, $city_query);
        $row = mysqli_fetch_array($city_results);
        $city = $row['id'];
    }

    $state_query = "SELECT id FROM `tbl_state` WHERE `name` = '$state'";
    $state_results = mysqli_query($db, $state_query);
    if (mysqli_num_rows($state_results) > 0) {
        $row = mysqli_fetch_array($state_results);
        $state = $row['id'];
    } else {
        $state_query = "INSERT INTO `tbl_state` (name) VALUES ('$state')";
        mysqli_query($db, $state_query);
        $state_query = "SELECT id FROM `tbl_state` WHERE `name` = '$state'";
        $state_results = mysqli_query($db, $state_query);
        $row = mysqli_fetch_array($state_results);
        $state = $row['id'];
    }


    $add_id = 999999;
    $add_query = "INSERT INTO `tbl_address` (street, unit_no, city_id, state_id, zip, is_active) 
  	       	       VALUES ('$street', '$unit_no', '$city', '$state', '$zip', 1)";
    if (mysqli_query($db, $add_query)) {
        $add_id_query = "SELECT id FROM  tbl_address WHERE street = '$street' AND unit_no = '$unit_no' AND city_id = '$city' and state_id = 
                      '$state'";
        $add_id_results = mysqli_query($db, $add_id_query);
        $row = mysqli_fetch_array($add_id_results);
        $add_id = $row['id'];
    }

        $res_query = "INSERT INTO tbl_restaurants (name, address_id, type_id, ph_no, rating, price_range, delivery_time, is_active)
                      VALUES ('$res_name', '$add_id', '$res_type', '$ph_no', '$rating', '$price_range', '$delivery_time', 1)";

    if (mysqli_query($db, $res_query)) {
        // Redirect to login page
        $id = mysqli_insert_id($db);
        $file_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($file_tmp, "images/$id.jpg");

        header("Location: new_item.php?id=$id");
    }

    }

?>

<html>
<head>
    <title>Add Restaurant</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="new_restaurant_script.js"></script>
</head>
<body>
<div class="wrapper">
    <div class="_3pYe-" style="height: 130px;">
        <div class="_1Tg1D">Add Restaurant</div>
        <div class="HXZeD"></div>

        <form id="restaurant_form" action="new_restaurant.php" method="POST" enctype="multipart/form-data">
            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="restaurant_name" name="r_name" class="_381fS">
                    <label class="_1Cvlf" for="restaurant_name">
                        Restaurant Name
                        <span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="street" name="street" class="_381fS">
                    <label class="_1Cvlf" for="street">Street<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="unit_no" name="unit_no" class="_381fS">
                    <label class="_1Cvlf " for="unit_no">Unit<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="city" name="city" class="_381fS">
                    <label class="_1Cvlf " for="city">City<span></span></label>
                </div>
            </div>


            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="state" name="state" class="_381fS">
                    <label class="_1Cvlf" for="state">State<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="zip" name="zip" class="_381fS">
                    <label class="_1Cvlf" for="zip">Zip<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="ph_no" name="ph_no" class="_381fS">
                    <label class="_1Cvlf " for="ph_no">Phone Number<span></span></label>
                </div>
            </div>

            <div>
                <div>
                    Restaurant Type
                    <select id="restaurant_type" name="res_type">
                        <?php while ($row = mysqli_fetch_assoc($res_results)) : ?>
                            <option value="<?php echo $row["id"] ?>">
                                <?php echo $row['type'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div>
                <div style="margin-bottom: 10px; padding: 10px 0px;">
                    Image <input type="file" name="image" accept="image/jpeg" style="position: relative;left: 125px;" />
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="price_range" name="price_range" class="_381fS">
                    <label class="_1Cvlf " for="price_range">Price Range<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="rating" name="rating" class="_381fS">
                    <label class="_1Cvlf " for="rating">Rating<span></span></label>
                </div>
            </div>

            <div>
                <div class="_3Um38 _3lG1r" data-children-count="1">
                    <input type="text" id="delivery_time" name="delivery_time" class="_381fS">
                    <label class="_1Cvlf " for="delivery_time">Delivery time<span></span></label>
                </div>
            </div>
            <input type="hidden" name="save" value="1"/>

            <div class="_25qBi _2-hTu" id="reg_btn"><a class="a-ayg"><input type="submit"
                                                                            style="display: none;">Add Restaurant</a>
            </div>
        </form>
    </div>
</body>
</html>
<!-- scripts -->
