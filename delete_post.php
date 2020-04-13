<?php
$page_ID="Delete Post";
include ('Website Stats/Website_stats.php');
require_once('includes/class-query.php');
require_once('includes/class-db.php');
include_once('error_log.php');
$mysqli = new mysqli('localhost','root','','cman');
$id = key($_GET);
$QRYDeletePost = "DELETE FROM `posts` WHERE `ID` = '$id'";
$DeletePost = mysqli_query($mysqli, $QRYDeletePost) or die(mysql_error);
header("Location: ../Complex CMS/index.php?deletesuccess")
?>

<html>
    <head>
        <title>Delete Post</title>
    </head>
    <body>
    <link rel="stylesheet" href="stylesheet.css">
    </body>
</html>