<?php

    include_once "db.php";
    include_once "checkLogin.php";

    $user_id = $_SESSION["id"];
    if(isset($_POST['address_id'])){
        $sql = "UPDATE tbl_orders SET address_id = ${_POST['address_id']}, updated_on = NOW() 
        WHERE user_id = $user_id AND address_id is NULL";
        
        $result = mysqli_query($db, $sql);
    }
    $sql =  "SELECT o.id, r.id as restaurant_id, r.name, o.updated_on, a.street, ar.street restaurant_street, 
    		count(od.order_id) as row_count
			FROM tbl_orders o 
			JOIN tbl_restaurants r on r.id = o.restaurant_id 
			JOIN tbl_address a on a.id = o.address_id 
			JOIN tbl_address ar on ar.id = r.address_id
			JOIN tbl_order_details od on od.order_id = o.id				
			WHERE o.user_id = $user_id AND o.address_id IS NOT NULL GROUP BY o.id ORDER BY o.updated_on DESC";

    

	$items =  	"SELECT  o.restaurant_id, o.id, i.name as item_name, od.qty as item_qty
				FROM tbl_orders o 			
				JOIN tbl_items i on i.restaurant_id = o.restaurant_id
				JOIN tbl_order_details od  on od.order_id = o.id AND od.item_id = i.id			
				WHERE o.user_id = $user_id AND o.address_id IS NOT NULL ORDER BY o.updated_on DESC";

    $items_result = mysqli_query($db, $items);
    $result = mysqli_query($db, $sql);

    $name_sql = "SELECT first_name FROM tbl_login WHERE id = $user_id";
	$name_results = mysqli_query($db, $name_sql);
	$row = mysqli_fetch_array($name_results);
	$name = $row['first_name'];

?>

<!DOCTYPE html>
<html class="fonts-loaded" lang="en">
<head>

    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>
<body>
<div id="root" style="height: 100%;">
    <div class="_3arMG">
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
                                <div class="_2CgXb _2ntM9">
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
            <div class="_1w2w1">
                <div class="_3tDvm ">
                    <div class="v6luz"></div>
                    <div class="_2QhOV _3glSS">
                        <div class="_3R9IF">
                            <div class="_2gu8R">
                                <ul>
                                    <li class="awo_x _1B5rE">
                                        <i class="fas fa-shopping-bag _3rA45 _34BwO"></i>
                                        <span class="_1ZYny ko2i4">Orders</span></li>
                                    <li class="awo_x ">
                                        <a href="logout.php">
                                            <i class="fas fa-sign-out-alt _3rA45"></i>
                                            <span class="_1ZYny ko2i4">Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="_1stFr">
                                <div>
                                    <div>
                                        <div class="_3lCtm"> Past Orders</div>                                        
                                        <?php while ($order = mysqli_fetch_array($result)): ?>
                                            <div>
                                                <div class="_3xMk0">
                                                    <div class="g28rk">
                                                        <div class="_359Fc"><a href="details.php?id=<?php echo  $order['restaurant_id'] ?>"><img
                                                                style="background-image: url(&quot;&quot;);"
                                                                class="_2tuBw _12_oN"
                                                                src="images/<?php echo  $order['restaurant_id'] ?>.jpg"
                                                                alt="" width="300" height="200"></a></div>
                                                        <div class="_2XWVq">
                                                            <div class="_3h4gz"><a href="details.php?id=<?php echo  $order['restaurant_id'] ?>"><?php echo $order['name'] ?></a></div>
                                                            <div class="_2haEe"><?php echo $order['restaurant_street']?></div>
                                                            <div class="_2uT6l">
                                                            	<div>ORDER #<?php echo $order['id'] ?>  | Delivered to <?php echo $order['street'] ?>
                                                            	</div>
                                                            	<div> 
                                                            		<?php $i = 1; ?>                                           		
                                                            		<?php while ($i <= $order['row_count'] && $item = mysqli_fetch_array($items_result)): ?> 			
                                                            				<?php echo $item['item_name'] ?> x <?php echo $item['item_qty']?><?php echo " || "; ?>
                                                            				<?php $i++;?>
                                                            		<?php endwhile; ?>
                                                            	</div>
                                                        	</div>
                                                            <div class="_2fkm7">
                                                                <span>Ordered on <?php echo $order['updated_on'] ?>
                                                                    <i class="fas fa-check h-Ntp"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_1a4Mf"></div>
                </div>
            </div>

        </div>
    </div>
    <div class="_2b6Ch">
        <div class="_3WqGq">
            <div class="_1vd_H">
                <div class="_2VSxh"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>