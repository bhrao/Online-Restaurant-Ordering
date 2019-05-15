<?php
$els = explode("/",$_SERVER['REQUEST_URI']);
$con = mysqli_connect("localhost", "root", "root", "wpl_project");
header('Content-Type: application/json');
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = "SELECT * FROM tbl_restaurants WHERE is_active=1";

if(isset($_GET['query'])){
    $query .= "AND name LIKE '%".$_GET['query'] ."%'";
}
$per_page =16;
if ($result=mysqli_query($con,$query))
{
    // Return the number of rows in result set
    $count=mysqli_num_rows($result);
    if(!isset($_GET['page'])){
        $page="1";
    }else{
        $page=$_GET['page'];
    }
    $pages = ceil($count/$per_page);

    $start = ($page - 1) * $per_page;
    $query .= " LIMIT $start,$per_page";
}
$result = mysqli_query($con, $query);
$out = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($out, array(
        "id" => $row["id"],
        "name" => $row["name"]
    ));
}

echo json_encode($out);
mysqli_close($con);