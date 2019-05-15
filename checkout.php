<?php

include_once "db.php";
include_once "checkLogin.php";

$user_id = $_SESSION["id"];


if(isset($_POST['street'])){
    $street = mysqli_real_escape_string($db, $_POST['street']);
    $unit_no = mysqli_real_escape_string($db, $_POST['unit_no']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $state = mysqli_real_escape_string($db, $_POST['state']);
    $zip = mysqli_real_escape_string($db, $_POST['zip']);

    $city_query = "SELECT id FROM `tbl_city` WHERE `name` = '$city'";
    $city_results = mysqli_query($db, $city_query);
    if(mysqli_num_rows($city_results) > 0){
      $row = mysqli_fetch_array($city_results);
      $city = $row['id'];
    } else{
      $city_query = "INSERT INTO `tbl_city` (name) VALUES ('$city')";
      mysqli_query($db, $city_query);
      $city_query = "SELECT id FROM `tbl_city` WHERE `name` = '$city'";
      $city_results = mysqli_query($db, $city_query);
      $row = mysqli_fetch_array($city_results);
      $city = $row['id'];
    }

    $state_query = "SELECT id FROM `tbl_state` WHERE `name` = '$state'";
    $state_results = mysqli_query($db, $state_query);
    if(mysqli_num_rows($state_results) > 0){
      $row = mysqli_fetch_array($state_results);
      $state = $row['id'];
    } else{
      $state_query = "INSERT INTO `tbl_state` (name) VALUES ('$state')";
      mysqli_query($db, $state_query);
      $state_query = "SELECT id FROM `tbl_state` WHERE `name` = '$state'";
      $state_results = mysqli_query($db, $state_query);
      $row = mysqli_fetch_array($state_results);
      $state = $row['id'];
    }

    $add_query = "INSERT INTO `tbl_address` (street, unit_no, city_id, state_id, zip, is_active) 
                   VALUES ('$street', '$unit_no', '$city', '$state', '$zip', 1)";

    if(mysqli_query($db, $add_query)){
        $last_id = mysqli_insert_id($db);

        $rl_add_user_query = "INSERT INTO `rl_address_user` (address_id, user_id) VALUES ($last_id, $user_id)";
        mysqli_query($db, $rl_add_user_query);
    }
}

$query = "SELECT a.id, a.street, a.unit_no, c.name city, s.name state, a.zip FROM tbl_address a 
JOIN rl_address_user au on au.address_id = a.id
JOIN tbl_login u on u.id = au.user_id
JOIN tbl_city c ON c.id = a.city_id JOIN tbl_state s on s.id = a.state_id 
WHERE u.id = $user_id AND a.is_active = true";

if($result = mysqli_query($db, $query)){
    $address = array();
    while ($item = mysqli_fetch_array($result)){
        array_push($address, array(
                "id" => $item['id'],
                "street" => $item['street'],
                "unit_no" => $item['unit_no'],
                "city" => $item['city'],
                "state" => $item['state'],
                "zip" => $item['zip']
        ));
    }
}

$sql = "SELECT i.name, i.price, i.id, od.qty FROM tbl_orders o 
            JOIN tbl_order_details od on o.id = od.order_id 
            JOIN tbl_items i ON od.item_id = i.id
            WHERE o.user_id = $user_id AND o.address_id is NULL";

$cart = array();
if($result = mysqli_query($db, $sql)){
    while ($item = mysqli_fetch_array($result)){
        array_push($cart, array(
            "id" => $item['id'],
            "qty" => $item['qty'],
            "name" => $item['name'],
            "price" => $item['price']
        ));
    }
}
if(count($cart) == 0){
    header("location: home.php");
    exit;
}

$sql = "SELECT r.id,r.name,a.street FROM tbl_orders o 
JOIN tbl_restaurants r ON o.restaurant_id = r.id 
JOIN tbl_address a ON a.id = r.address_id 
WHERE o.user_id = $user_id AND o.address_id is NULL";
$res = array();
if($result = mysqli_query($db, $sql)){
    $res = mysqli_fetch_array($result);
}

$name_sql = "SELECT first_name FROM tbl_login WHERE id = $user_id";
$name_results = mysqli_query($db, $name_sql);
$row = mysqli_fetch_array($name_results);
$name = $row['first_name'];


?>

<!DOCTYPE html>
<html class="fonts-loaded" lang="en">
<head>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body class="">
<div id="root" style="height: 100%;">
    <div class="root-wrapper">
        <header class="_76q0O">
            <div class="global-nav">
                <div class="_1EuBh">
                    <div class="_13TKm">
                        <a class="_1T-E4" href="home.php">
                            Dallas Food
                        </a>
                    </div>
                    <ul class="_1JNGZ">
                        <li class="_1fo6c">
                            <div class="_2CgXb _2ntM9">
                                <a class="_1T-E4" href="checkout.php">
                                    <span class="_3yZyp">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <span>Cart</span>
                                </a>
                            </div>
                        </li>
                        <li class="_1fo6c">
                            <div class="_2CgXb">
                                <a class="_1T-E4" href="account.php">
                                    <span class="_3yZyp">
                                        <i class="far fa-user"></i>
                                    </span>
                                    <span>Account</span>
                                </a>

                            </div>
                        </li> 
                    </ul>
                </div>
            </div>
        </header>
        <div class="nDVxx _340-t">
            <div class="_3-bcQ">
                <div class="_3djal">
                    <div>
                        <div class="_1rwo5 ">
                            <div class="F8Sye">
                                <div class="_2YrH-">Choose a delivery address</div>
                            </div>
                            <div>
                                <div class="-brc1">
                                    <?php foreach($address as $add): ?>
                                    <div class="_2nd--">
                                        <div class="_3p8Mf">
                                            <div class="WtfuC _3mJDe"><i class="fas fa-map-marker-alt"></i></div>
                                            <div>
                                                <div class="_2xgU6"><?php echo $add['street'] ?></div>
                                                <div class="KYAcN"><?php echo $add['unit_no']. ", ". $add['city']. ", ".
                                                        $add['state'] . ", ". $add['zip']?>
                                                </div>
                                                <div class="deliver _3dNWs" data-addid="<?php echo $add['id'] ?>">Deliver Here</div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <div class="_2nd--">
                                        <div class="_3p8Mf Ldi91">
                                            <div class="WtfuC _3mJDe">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <div class="_2_VIS">+</div>
                                            </div>
                                            <div id="add_form" >
                                                <form id="my-form" action="" method="POST">
                                                <div class="_2xgU6">
                                                    <input type="text" id="street" name="street" placeholder="street" " />
                                                    <span></span>
                                                </div>
                                                <div class="KYAcN">                                                
                                                    <input type="text" id="unit_no" name="unit_no" placeholder="Apt/unit" />
                                                    <input type="text" id="city" name="city" placeholder="city" />
                                                    <input type="text" id="state" name="state" placeholder="state" /> 
                                                    <input type="text" id="zip" name="zip" placeholder="zip code" />
                                                    <div id="add" class="_3dNWs">Add</div>
                                                    <div class="cancel-button">Cancel</div>
                                                </div>
                                                </form>
                                            </div>
                                            <div id="add_placeholder">
                                                <div class="_2xgU6">Add new Address</div>
                                                <div class="KYAcN">...</div>
                                                <div id="add_new" class="_3dNWs _1AS3P">Add New</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="_250uQ _26MRf"></div>
                            <div class="_2b4pY">
                                <span class="_1q8J4 ">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="_2sMsA">
                    <div class="_1LDW5">
                        <div class="_1mJeT">
                            <a href="details.php?id=<?php echo $res['id']?>">
                            <div class="_1dcmE"><img style="background-image: url(&quot;&quot;);" class="_2tuBw _12_oN"
                                                     alt=""
                                                     src="images/<?php echo $res['id'] ?>.jpg"
                                                     width="50" height="50"></div>
                            <div class="u1PgV">
                                <div class="V7Usk"><?php echo $res['name']?></div>
                                <div class="_2ofXa"><?php echo $res['street']?></div>
                            </div>
                            </a>
                        </div>
                        <div class="_1t-Al">
                            <div class="_3YMqW"></div>
                            <div class="_2ObNr _2Y5ZT _2XVjJ _1S7oI">
                                <div>
                                    <div class="_2zsON"></div>
                                    <div class="_2pdCL">

                                    </div>
                                    <div class="_3PZFF">
                                        <div class="_3e0Qi">Bill Details</div>
                                        <div class="_3rlIu">
                                            <div class="_2VV4a"><span>Item Total</span></div>
                                            <div class="_1I8bA"><span class=""><span></span><span
                                                   id="subtotal" class="ZH2UW"></span></span></div>
                                        </div>
                                        <div class="_1Accg"></div>
                                        <div class="_3rlIu">
                                            <div class="_2VV4a">Delivery Fee
                                                <div class="_3sNvC">
                                                    <i class="fas fa-info-circle"></i>
                                                    <div class="_28dQ0">
                                                        <div class="_1p8D9" style="width: 290px;">
                                                            <div class="_255F-">
                                                                <div class="_3PZFF">
                                                                    <div class="_3e0Qi">Delivery fee breakup for this
                                                                        order
                                                                    </div>
                                                                    <div class="_3rlIu">
                                                                        <div class="_2VV4a">
                                                                            <div>Standard Fee</div>
                                                                            <div class="MOQEt">$2.99
                                                                            </div>
                                                                        </div>
                                                                        <div class="_1I8bA"><span class=""><span></span><span
                                                                                class="ZH2UW">2.99</span></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="_1I8bA"><span class=""><span></span><span
                                                    class="ZH2UW">2.99</span></span></div>
                                        </div>
                                    </div>
                                    <div class="_3DPdG"></div>
                                </div>
                            </div>
                            <div class="_1v28S _2Cjz6"></div>
                        </div>
                        <div class="ZBf6d">
                            <div>TO PAY</div>
                            <div class="_3ZAW1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="redirect" style="display: hidden" method="post" action="account.php">
        <input id="ra" name="address_id" />
    </form>
</div>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        var oldcart = $.parseJSON('<?php echo addslashes(json_encode($cart)); ?>');
        displayCart(oldcart);

        function updateCart(el,type, n){
            var t = $(el.target);
            var val = t.siblings("._2zAXs")
            var curr = parseInt(val.html());
            var item_id = t.parent().data('item-id');
            $.post("update_cart.php", {
                cart_update: {
                    restaurant_id : <?php echo $res['id']; ?>,
                    item_id: item_id,
                    quantity: curr + type
                }
            }, function (data){
                displayCart(data);
            });
        }

        $("._2pdCL").on('click', "._1ds9T", function(el){
            updateCart(el, 1, 0);
        });
        $("._2pdCL").on('click', "._29Y5Z", function (el) {
            updateCart(el, -1,0);
        });

        function displayCart(cart) {
            var cartDiv = $('._2pdCL');
            cartDiv.html('');
            var subtotal = 0;


            $.each(cart, function (i, e) {
                var tmp = (parseFloat(e.price) * parseInt(e.qty));
                subtotal += tmp;
                var cartItem = $('<div class="_2bXOy">'+
                    '<div class="_3SG03"><div class="_33KRy">'+  e.name +' </div></div>'+
                    '<div class="_2bWmk"><div class="_1yTZI">' +
                    '<div class="_29ugw _3L1X9" data-item-id=' + e.id +  '>'+
                    '<div class="_1ds9T">+</div>'+
                    '<div class="_29Y5Z"></div>'+
                    '<div class="_2zAXs">' + e.qty + '</div></div>'+
                    '<div class="_1mx0r"><span class="_2W2U4">' + tmp.toFixed(2) +'</span></div>'+
                    '</div></div></div>');
                cartDiv.append(cartItem);
            });
            $("#subtotal").html(subtotal.toFixed(2));
            var total = subtotal + 2.99;
            $("._3ZAW1").html(total.toFixed(2));
        }
        $("#add_form").hide();

        $("#add_new").click(function(){
            $("#add_placeholder").hide(300);
            $("#add_form").show(300);
        });

        $('.cancel-button').click(function(){
            $("#add_placeholder").show(300);
            $("#add_form").hide(300);
        });


        $('.deliver').click(function(){
            var id = $(this).data('addid');
            $("#ra").val(id);
            $("#redirect").submit();
        });         
            var street_state = false;
            var unit_no_state = false;
            var city_state = false;
            var state_state = false;
            var zip_state = false;                    

            $('#street').on('blur', function(){console.log("inside street");
              var street = $('#street').val();                          
              if (street == '') {
                street_state = false;
                $('#street').removeClass("form_success");
                $('#street').addClass("form_error");                                  
              }else{
                street_state = true;
                $('#street').removeClass("form_error");
                $('#street').addClass("form_success");                
              }
            });

            $('#unit_no').on('blur', function(){
              var unit_no = $('#unit_no').val();                          
              if (unit_no == '') {
                unit_no_state = false;
                $('#unit_no').removeClass("form_success");                
                $('#unit_no').addClass("form_error");                
                return;                      
              }else{
                unit_no_state = true;
                $('#unit_no').removeClass("form_error");
                $('#unit_no').addClass("form_success");                
              }
            });

            $('#city').on('blur', function(){
              var city = $('#city').val();              
              if (city == '') {
                city_state = false;
                $('#city').removeClass("form_success");
                $('#city').addClass("form_error");                
                return;                      
              }else{
                city_state = true;
                $('#city').removeClass("form_error");
                $('#city').addClass("form_success");                
              }
            });

            $('#state').on('blur', function(){
              var state = $('#state').val();              
              if (state == '') {
                state_state = false;
                $('#state').removeClass("form_success");
                $('#state').addClass("form_error");                
                return;                      
              }else{
                state_state = true;
                $('#state').removeClass("form_error");
                $('#state').addClass("form_success");                
              }
            });

            $('#zip').on('blur', function(){
              var zip = $('#zip').val();              
              if (zip == '') {
                zip_state = false;
                $('#zip').removeClass("form_success");
                $('#zip').addClass("form_error");                
                return;                      
              }else{
                zip_state = true;
                $('#zip').removeClass("form_error");
                $('#zip').addClass("form_success");                
              }
            });

            $('#add').on('click', function(e){
              e.preventDefault();              
              var street = $('#street').val();
              var unit_no = $('#unit_no').val();
              var city = $('#city').val();
              var state = $('#state').val();
              var zip = $('#zip').val();                
              if (street_state == false || city_state == false || unit_no_state == false || state_state == false || zip_state == false) {
               alert("Form not filled!!");
              } else{
                
                    $("#my-form").submit();
                }
            });

        });
</script>
</body>
</html>