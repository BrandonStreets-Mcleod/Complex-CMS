<?php
$page_ID="Logout";
include ('Website Stats/Website_stats.php');
session_start();//runs function to start session 
include_once('error_log.php');

$_SESSION = array();//loads session array with user data in

session_destroy();//destroys session once user has logged out.

$_SESSION['editmode'] = 0;

header("location: index.php");//Once session is started it then redirects to login.php
exit;
?>