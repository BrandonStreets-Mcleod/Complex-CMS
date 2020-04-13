<?php
session_start();//runs function to start session 

$_SESSION = array();//loads session array with user data in

session_destroy();//destroys session once user has logged out.

header("location: index.php");//Once session is started it then redirects to login.php
exit;
?>