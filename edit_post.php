<?php
$page_ID="Edit Post";
include ('Website Stats/Website_stats.php');
session_start();
require_once('includes/class-query.php');
require_once('includes/class-db.php');
include_once('error_log.php');
$mysqli = new mysqli('localhost','root','','cman');
$id = key($_GET['content']);
$content = $_GET['content'][$id];
$QRYEditContent = "UPDATE `posts` SET post_content = '".$content."' WHERE ID = '$id'";
$EditContent = mysqli_query($mysqli, $QRYEditContent) or die(mysql_error);
header("Location: ../Complex CMS/index.php?updatesuccess")
?>
<html>
    <head>
        <title>Edit Post</title>
    </head>
    <body>
    <link rel="stylesheet" href="stylesheet.css">
    </body>
</html>
