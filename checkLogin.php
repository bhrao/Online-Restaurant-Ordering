<?php
/**
 * Created by PhpStorm.
 * User: yaswanthyadlapalli
 * Date: 2019-04-24
 * Time: 17:08
 */

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
    header("location: index.php");
    exit;
}