<?php
/**
 * Created by PhpStorm.
 * User: yaswanthyadlapalli
 * Date: 2019-04-29
 * Time: 21:45
 */

include_once "db.php";
include_once "checkLogin.php";

if(!isset($_GET['id']))
    header('Location: home.php');
else
    $id = $_GET['id'];

if($_SESSION["admin"] != 1){
    header("location: home.php");
    exit;
}
$query = "UPDATE tbl_restaurants SET is_active = 0 WHERE id = $id ";

$result = mysqli_query($db, $query);
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

