<?php


    if(!isset($_GET['id']))
        header('Location: home.php');
    else
        $id = $_GET['id'];
    include_once "db.php";
    include_once "checkLogin.php";

    $user_id = $_SESSION["id"];

    $query = "SELECT c.name cat,i.id, i.name, i.price, i.description  FROM tbl_restaurants r JOIN tbl_items i on r.id = i.restaurant_id JOIN tbl_category c on c.id = i.category_id  WHERE r.id = $id AND r.is_active = 1 ORDER BY c.id, i.name";

    $resDetails = "SELECT r.*,a.street, a.unit_no, a.zip, s.name state, c.name city   FROM tbl_restaurants r JOIN tbl_address a on a.id = r.address_id JOIN tbl_city c ON c.id = a.city_id JOIN tbl_state s on s.id = a.state_id  WHERE r.id = $id AND r.is_active = 1" ;

    $result = mysqli_query($db, $query);
    $data = array();
    $flag = false;
$first_cat = "";
    while ($row = mysqli_fetch_array($result)){
        $item = array(
            "name" => $row['name'],
            "price" => $row['price'],
            "desc" => $row['description'],
            "id" => $row['id']
        );
        if(!isset($data[$row['cat']])){
            if($flag == false){
                $flag = true;
                $first_cat = $row['cat'];
            }
            $data[$row['cat']] = array($item);
        }
        else{
            array_push($data[$row['cat']], $item);
        }
    }
    if(count($data) == 0){
        header("location: home.php");
        exit;
    }

    if($result = mysqli_query($db, $resDetails)){
        $rest = mysqli_fetch_array($result);
    }
    $f = true;

    $sql = "SELECT i.name, i.price, i.id, od.qty FROM tbl_orders o 
            JOIN tbl_order_details od on o.id = od.order_id 
            JOIN tbl_items i ON od.item_id = i.id 
            WHERE o.user_id = $user_id AND o.address_id is NULL";

    if($result = mysqli_query($db, $sql)){
        $cart = array();
        while ($item = mysqli_fetch_array($result)){
            array_push($cart, array(
                "id" => $item['id'],
                "qty" => $item['qty'],
                "name" => $item['name'],
                "price" => $item['price']
            ));
        }
    }
?>


<!DOCTYPE html>
<html class="fonts-loaded" lang="en">
<head>
    <link rel="stylesheet"
          href="style.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

</head>
<body>
<div id="root" style="height: 100%;">
    <div class="root-wrapper">
        <header class="_76q0O _1gydB">
            <div class="global-nav">
                <div class="_1EuBh">
                    <div class="_13TKm">
                        <a class="_1T-E4" href="home.php">
                            Dallas Food
                        </a>
                    </div>

                    <ul class="_1JNGZ">
                        <li class="_1fo6c">
                            <div class="_2CgXb">
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
        <div class="nDVxx">
            <div class="uSag_">
                <div class="_1637z">
                    <div class="_8MlDE">
                        <div class="_3TBoD">
                            <div class="_3mJdF">
                                <div class="H5I6J">
                                    <img style="background-image: url(&quot;&quot;);"
                                         class="_2tuBw _12_oN _3sCWI"
                                         src="images/<?php echo $id ?>.jpg"
                                         alt="" width="254" height="165">
                                </div>
                            </div>
                            <div class="_2Fixt">
                                <div class="_1WDSQ">
                                    <div class="U-jcg">
                                        <div class="OEfxz"><h1 title="$rest['name']" class="_3aqeL">
                                                <?php echo $rest['name'] ?>
                                            </h1></div>
                                    </div>
                                    <div class="_2cMZ_">
                                        <span class="_20F32">
                                            <span>
                                                <i class="fas fa-star _2n5YQ"></i>
                                                <?php echo  $rest['rating']?>
                                            </span>
                                        </span>
                                        <span class="_20F32">
                                            <span class="_27qo_"><?php echo $rest['delivery_time'] ?> MINS</span>
                                        </span>
                                        <span class="_20F32">
                                            <span><?php for ($j = 0; $j < $rest['price_range']; $j++) echo "$"; ?></span>
                                        </span>
                                    </div>
                                    <div class="_1BpLF">
                                        <div class="Gf2NS _2Y6HW"><?php echo $rest['street']."," ?>
                                            <span class="_2JILy"> <?php echo $rest['city']?></span></div>
                                        <div class="_3Plw0 JMACF">
                                        </div>
                                        <div class="_2aZit _2fC4N">
                                            <div class="_2iUp9 ">
                                                <div class="_2l3H5">
                                                    <span>
                                                        <i class="fas fa-star _2n5YQ"></i>
                                                        <?php echo  $rest['rating']?>
                                                    </span>
                                                </div>
                                                <div class="_1De48"><span class="_1iYuU">10000+ ratings</span></div>
                                            </div>
                                            <div class="_2iUp9 ">
                                                <div class="_2l3H5"><span class="_27qo_"><?php echo $rest['delivery_time'] ?> MINS</span></div>
                                                <div class="_1De48">Delivery Time</div>
                                            </div>
                                            <div class="_2iUp9 ">
                                                <div class="_2l3H5"><span><?php for ($j = 0; $j < $rest['price_range']; $j++) echo "$"; ?></span></div>
                                                <div class="_1De48">Cost </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Z4sK8"></div>
                        </div>
                    </div>
                </div>
                <div id="menu-content" class="_1hM1R znxoh">
                    <div class="_1okhE">
                        <div class="_1srfG">
                            <div class="_2mKMa">
                                <div class="_1JVzD">
                                    <div class="nh_z0" style="margin-right: -15px; padding-right: 15px;">
                                        <div class="_2K_ax" style="padding-right: 30px;">
                                            <?php foreach ($data as $cat => $items): ?>
                                                <div id= "i-<?php echo hash('adler32',$cat)?>" data-target="<?php echo hash('adler32',$cat)?>" class="D_TFT"><?php echo $cat?></div>
                                            <?php endforeach; ?>
                                            <div class="_2HWyL" style="transform: translateY(0px);"></div>
                                        </div>
                                    </div>
                                    <div class="_1LxRl">
                                        <div class="_30cCo"
                                             style="transform: translateY(0px); height: 0px; visibility: hidden; opacity: 0;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="_1J_la">
                            <?php foreach ($data as $cat => $items): ?>
                                <div id="h-<?php echo hash('adler32',$cat) ?>" class="_2dS-v">
                                    <h2 class="M_o7R _27PKo"><?php echo $cat ?></h2>
                                    <div class="Yu6Bn"><?php echo sizeof($items) ?> items</div>
                                    <div>
                                        <?php foreach ($items as $item): ?>
                                            <div class="_2wg_t">
                                                <div>
                                                    <div class="GaqmA">
                                                        <div>
                                                            <div class="_1G3G4 _3L1X9" id="<?php echo $item['id'] ?>" data-item-id="<?php echo $item['id']; ?>">
                                                                <div class="_1RPOp">ADD</div>
                                                                <div class="_1ds9T _2Thnf _4aKW6">+</div>
                                                                <div class="_29Y5Z _2od4M _4aKW6"></div>
                                                                <div class="_2zAXs _18lJJ _4aKW6">0</div>
                                                            </div>
                                                            <div class="_19GqV">
                                                                <div class="_2Gojq">
                                                                    <div class="jTy8b"
                                                                         itemprop="name"><?php echo $item['name'] ?></div>
                                                                </div>
                                                                <div class="_12lpv MwITc"><span
                                                                            class="bQEAj"><?php echo $item['price'] ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div></div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="_5ZwHn">
                        <div class="_1WKwn">
                            <div class="_2uq6N">Cart
                                <div class="_1XFmX">0 Items</div>
                            </div>
                            <div class="_1t-Al _1XUXj">
                                <div class="_3YMqW"></div>
                                <div class="_2ObNr _2qOpI">
                                    <div>
                                        <div class="_2zsON"></div>
                                        <div class="MGAj1">
                                        </div>
                                        <div class="_3DPdG"></div>
                                    </div>
                                </div>
                                <div class="_1v28S _2Cjz6"></div>
                            </div>
                            <div class="EEeV3">
                                <div class="_161V3">
                                    <div class="_1DWmI">Subtotal</div>
                                    <div class=""><span class="_2W2U4">0.00</span></div>
                                </div>
                                <a id="checkout" href="checkout.php">
                                    <div class="_1gPB7">Checkout →</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="_1K7MM">
                <div class="_3WFcw _3Vzaj">
                    <div class="_22ZY1">
                        <i class="fas fa-arrow-up _3c8Hp"></i>
                    </div>
                    <span class="tDxj3"></span></div>
            </div>
        </div>
    </div>
</div>

<div id="modal-placeholder">
    <div></div>
</div>
<div id="overlay-sidebar-root"></div>
<div id="sticky-notifications"></div>
<script>
    var toggled = false;
    var restaurant_id = <?php echo $_GET['id']; ?>;
    var confirm = false;



    $(document).ready(function () {
        var oldcart = $.parseJSON('<?php echo addslashes(json_encode($cart)); ?>');
        displayCart(oldcart);
        var hashes= <?php
            echo "[";
            foreach($data as $cat => $key) {
                if($f)
                    $f = false;
                else
                    echo ",";
                echo '"' . hash('adler32', $cat) . '"';
            }
            echo "]";
            ?>;
        var off = [];
        for(var i = 0; i< hashes.length; i++){
            off[i] = $("#h-"+hashes[i]).offset().top - 500;
        }
        onScrollHandler();
        var current_highlight = "<?php echo hash('adler32',$first_cat); ?>";
        $(window).scroll(onScrollHandler);

        $("._3WFcw").click(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: 0
            }, 200);
        });
        function onScrollHandler(){
            if (!toggled && $(document).scrollTop() > 140) {
                toggled = true;
                $('._1637z').toggleClass('Fy0A8');
                $('._8MlDE').toggleClass('_1qF_3');
                $('._3TBoD').toggleClass('_32T6U');
                $('.H5I6J').toggleClass('_2jfqu');
                $('._2tuBw._12_oN._3sCWI').toggleClass('_12LfL');
                $('._2Fixt').toggleClass('_1uH8g');
                $('._3aqeL').toggleClass('_3YHmy');
                $('._2cMZ_').toggleClass('_1B60w');
                $('._1BpLF').toggleClass('_1oxxe');
                $('._3WFcw').removeClass('_3Vzaj');

            }
            if (toggled && $(document).scrollTop() < 140) {
                toggled = false;
                $('._1637z').toggleClass('Fy0A8');
                $('._8MlDE').toggleClass('_1qF_3');
                $('._3TBoD').toggleClass('_32T6U');
                $('.H5I6J').toggleClass('_2jfqu');
                $('._2tuBw._12_oN._3sCWI').toggleClass('_12LfL');
                $('._2Fixt').toggleClass('_1uH8g');
                $('._3aqeL').toggleClass('_3YHmy');
                $('._2cMZ_').toggleClass('_1B60w');
                $('._1BpLF').toggleClass('_1oxxe');
                $('._3WFcw').addClass('_3Vzaj');
            }


            for(i=0; i<hashes.length ; i++){
                if($(document).scrollTop() < off[i]){
                    break;
                }
            }
            if(i == 0) {
                i = 1;
            }
            if (current_highlight != hashes[i - 1]){
                $("#i-" +current_highlight).removeClass("_2BbB0");
                $("#i-" + hashes[i - 1]).addClass("_2BbB0");
                $("._2HWyL").css("transform", "translateY("+ (28 * (i - 1)) + "px)");
                current_highlight = hashes[i - 1];
            }
        }

        $(".D_TFT").click(function (el) {
            var currEl  =$(el.target);
            var new_highlight = currEl.data('target');
            if(current_highlight != new_highlight){
                $([document.documentElement, document.body]).animate({
                    scrollTop: ($("#h-" + new_highlight).offset().top - 150)
                }, 200);
            }
            return false;
        });

        $("#menu-content").on('click', "._1RPOp", function(el){
            updateCart(el, 0, 1);
        });
        $("#menu-content").on('click', "._1ds9T", function(el){
            updateCart(el, 1, 0);
        });
        $("#menu-content").on('click', "._29Y5Z", function (el) {
            updateCart(el, -1,0);
        });


        function updateCart(el,type, n){
            var t = $(el.target);
            var val = t.siblings("._2zAXs")
            var curr = n > 0 ? 1 : parseInt(val.html());
            var item_id = t.parent().data('item-id');
            $.post("update_cart.php", {
                cart_update: {
                    restaurant_id : restaurant_id,
                    item_id: item_id,
                    quantity: curr + type
                }
            }, function (data){
                displayCart(data);
            });
        }
        function displayCart(cart) {
            if(cart.length == 0){
                $("#checkout").hide();
            }
            else{
                $("#checkout").show();
            }
            var cartDiv = $('.MGAj1');
            $("._1XFmX").html(cart.length +" items")
            cartDiv.html('');
            var subtotal = 0;

            $("._1G3G4 ._1RPOp").removeClass("_4aKW6");
            $("._1G3G4 :not(._1RPOp)").addClass("_4aKW6");


            $.each(cart,function(i, e){
                var selector = $("#" + e.id);
                if(selector.length == 1){
                    selector.children("._1RPOp").addClass("_4aKW6");
                    selector.children(":not(._1RPOp)").removeClass("_4aKW6");
                    selector.children("._2zAXs").html(e.qty);
                }
                else{
                    console.log("Cart from different restuarant")
                    confirm = true;
                }
                var tmp = (parseFloat(e.price) * parseInt(e.qty));
                subtotal += tmp;
                var cartItem = $('<div class="_2bXOy"><div class="_3SG03"><div class="_2MJB6"></div>' +
                    '<div class="_33KRy">' + e.name +'</div>' +
                    '</div><div class="_2bWmk"><div class="_1yTZI"><div class="_29ugw _3L1X9" data-item-id='+ e.id +'>' +
                    '<div class="_1RPOp _36fT9 _4aKW6">ADD</div>\n' +
                    '<div class="_1ds9T">+</div>\n' +
                    '<div class="_29Y5Z"></div>\n' +
                    '<div class="_2zAXs">' + e.qty + '</div>' +
                    '</div><div class="_1mx0r"><span class="_2W2U4">'+
                    tmp.toFixed(2) +'</span></div></div></div></div>');
                cartDiv.append(cartItem);
            });
            $("._161V3 ._2W2U4").html(subtotal.toFixed(2));
        }
    });




</script>
</body>
</html>