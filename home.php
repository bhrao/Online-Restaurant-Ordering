<?php

include_once "db.php";
include_once "checkLogin.php";

$admin = false;
if ($_SESSION["admin"] == 1) {
    $admin = true;
}
$res_type = "";
if (isset($_GET['restaurant_type'])) {
    if($_GET['restaurant_type'] != "0") {
        $res_type = $_GET['restaurant_type'];
    }
}
$search_q = "";
if (isset($_GET['query']))
    $search_q = mysqli_real_escape_string($db, $_GET['query']);
$query = "SELECT DISTINCT tr.*, rt.type
          FROM tbl_restaurants tr
          JOIN tbl_restaurant_type rt ON rt.id = tr.type_id 
          INNER JOIN tbl_items ti ON tr.`id` = ti.`restaurant_id`
          WHERE tr.`is_active` = 1 ";

if ($search_q != "") {
    $query .= "AND (tr.name LIKE '%" . $search_q . "%' OR ti.name LIKE '%" . $search_q . "%')";
}
if ($res_type != "") {
    $query .= "AND tr.type_id = " . $res_type;
}
$query .= " ORDER BY `tr`.`rating`, `tr`.`delivery_time`";

$per_page = 16;
if ($result = mysqli_query($db, $query)) {
    // Return the number of rows in result set
    $count = mysqli_num_rows($result);
    if (!isset($_GET['page'])) {
        $page = "1";
    } else {
        $page = $_GET['page'];
    }
    $pages = ceil($count / $per_page);
    $start = ($page - 1) * $per_page;
    $query .= " LIMIT $start,$per_page";
}
$result = mysqli_query($db, $query);
$i = 0;

$sql = "SELECT * FROM tbl_restaurant_type";

$cats = mysqli_query($db, $sql);

$selected_category = "All";

?>

<html class="fonts-loaded" lang="en">
<head>
    <link rel="stylesheet" href="style.css">
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
                            <div class="_2CgXb">
                                <a class="_1T-E4" href="account.php">
                                    <span class="_3yZyp">
                                        <i class="far fa-user"></i>
                                    </span>
                                    <span>Account</span>
                                </a>

                            </div>
                        </li>
                        <li class="_1fo6c">
                            <div>
                                <form method="GET" style="margin: 0px;">
                                    <input type="hidden" name="restaurant_type" id="res_type"
                                           value="<?php echo $res_type ?>"/>
                                    <input type="text" name="query" placeholder="Search"
                                           value="<?php echo($search_q); ?>">
                                    <input type="submit" value="Search"/>
                                </form>
                            </div>
                        </li>

                        <li class="_1fo6c">
                            <div class="_2438Q">
                                <ul id="dropdown" class="_23EJe">
                                    <li class="_364W3 <?php if ($res_type == "") echo "_2JKM1" ?>" data-id="0">
                                        All
                                        <?php if ($res_type == ""): ?>
                                            <i class="fas fa-check _2VnAV"></i>
                                        <?php endif; ?>
                                    </li>
                                    <?php while ($row = mysqli_fetch_assoc($cats)) : ?>
                                        <li class="_364W3
                                                            <?php if ($res_type == $row["id"]) {
                                            echo "_2JKM1 ";
                                            $selected_category = $row["type"];
                                        } ?>" data-id="<?php echo $row["id"] ?>">
                                            <?php echo $row['type'] ?>
                                            <?php if ($res_type == $row["id"]): ?>
                                                <i class="fas fa-check _2VnAV"></i>
                                            <?php endif ?>

                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                                <a class="_13TKm">
                                    <span id="choosen"><?php echo $selected_category ?></span>
                                    <i class="fas fa-chevron-down _3ycTq"></i>
                                </a>
                            </div>
                        </li>
                        <?php if($admin) :?>
                            <li class="_1fo6c">
                                <div class="_2CgXb">
                                    <a class="_1T-E4" href="new_restaurant.php">
                                    <span class="_3yZyp">
                                        <i class="fas fa-utensils"></i>
                                    </span>
                                        <span>New restaurant</span>
                                    </a>

                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </header>
        <div class="nDVxx">
            <div class="_3kbpE">
                <div class="_29kDH _3pFoM">
                    <div class="_1LV_f undefined" id="all_restaurants">
                        <div class="_10p2-">

                            <div>
                                <div class="_3A8UQ">
                                    <div class="_1Las2">
                                        <div class="restaurant-row">
                                            <?php while ($row = mysqli_fetch_array($result)): ?>
                                            <?php if ($i > 0 && ($i % 4 == 0)): ?>
                                        </div>
                                        <div class="restaurant-row">
                                            <?php endif; ?>
                                            <div class="restaurant">

                                                <?php if ($admin): ?>
                                                    <a class="add link" href="new_item.php?id=<?php echo $row['id'] ?>">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                    <a class="edit link" href="edit_restaurant.php?id=<?php echo $row['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a class="delete link"
                                                       href="delete.php?id=<?php echo $row['id'] ?>">
                                                        <i class="fas fa-times-circle"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="details.php?id=<?php echo $row['id'] ?>"
                                                   class="_1j_Yo">
                                                    <div class="_1HEuF">
                                                        <div class="_3FR5S">
                                                            <div class="efp8s"><img
                                                                        style="background-image: url(&quot;&quot;);"
                                                                        class="_2tuBw _12_oN"
                                                                        alt="<?php echo $row['name']; ?>"
                                                                        src="images/<?php echo $row['id'] ?>.jpg"
                                                                        width="254" height="160"></div>
                                                            <div class="_3Ztcd">
                                                                <div class="nA6kb"><?php echo $row['name']; ?> </div>
                                                                <div class="_1gURR"><?php echo $row['type']; ?></div>
                                                            </div>
                                                            <div class="_3Mn31">
                                                                <div class="_9uwBC wY0my">
                                                                    <i class="fas fa-star _537e4"></i>
                                                                    <span><?php echo round($row['rating'], 1); ?></span>
                                                                </div>
                                                                <div><?php echo $row['delivery_time'] ?> MINS</div>
                                                                <div class="nVWSi">
                                                                    <?php for ($j = 0; $j < $row['price_range']; $j++) echo "$"; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <?php $i++;
                                            endwhile; ?>

                                        </div>
                                        <div class="pagination">
                                            <?php for ($i = 1; $i <= $pages; $i++) : ?>
                                                <a role="button"
                                                   aria-label="Go to page <?php echo $i; ?>"
                                                   class="_1FZ7A <?php echo $i == $page ? "lh9t3" : ""; ?>"
                                                   href="<?php echo $i == $page ? "#" : ("?page=" . $i); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            var toggle = false;
            var selected = "<?php echo $res_type ?>";
            if (selected == "") {
                selected = "0";
            }
            $("._3ycTq").click(function () {
                if (!toggle) {
                    toggle = true;
                    $("#dropdown").addClass("GZzI3");
                }
            });
            $("._364W3").click(function () {
                var id = $(this).data("id");
                if (selected != id) {
                    var text = $(this).html();
                    var seletedLi = $("._364W3._2JKM1");
                    seletedLi.children("i").appendTo($(this));
                    seletedLi.removeClass("_2JKM1");
                    $(this).addClass("_2JKM1");
                    $("#res_type").val(id);
                    selected = id;
                    $("#choosen").html(text);
                }
                $("#dropdown").removeClass("GZzI3");
                toggle = false;
            })
        });
    </script>
</body>
</html>