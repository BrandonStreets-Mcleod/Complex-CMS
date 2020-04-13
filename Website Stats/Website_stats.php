<?php
session_start();
if (!isset($_SESSION['id']))//checks if session id has been set and if not then assigns session id to session
{
  $_SESSION['id']=session_id();
}

$con = mysqli_connect("localhost","root","","sitestats");//initiates SQL DB connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MYSQL: " . mysqli_connect_error();//error message
}

//PAGE REFERER STORE
$referer = "";
//the IF ELSEIF statements below simply check and store the page referer
if ($_SERVER['HTTP_REFERER'] == 'http://localhost:8008/projects/Brandon/PHP%20Tutorials/Website%20Stats/stats.php')
{
  $referer = 'Stats';
}
else if ($_SERVER['HTTP_REFERER'] == 'http://localhost:8008/projects/Brandon/PHP%20Tutorials/Website%20Stats/link1.php')
{
  $referer = 'Link 1';
}
else if ($_SERVER['HTTP_REFERER'] == 'http://localhost:8008/projects/Brandon/PHP%20Tutorials/Website%20Stats/link2.php')
{
  $referer = 'Link 2';
}
else if ($_SERVER['HTTP_REFERER'] == 'http://localhost:8008/projects/Brandon/PHP%20Tutorials/Website%20Stats/index.php')
{
  $referer = 'Homepage';
}
$query = "INSERT into `page_impressions` (session_ID, IP, visit_page, prev_page) VALUES ('".$_SESSION['id']."', '".$_SERVER['REMOTE_ADDR']."', '".$page_ID."', '".$referer."');";
$result = mysqli_query($con,$query);

//executes query to select all data from DB and finds the number of rows
$QRYvisits = "SELECT `visit_ID`, `session_ID`, `IP`, `visit_page`, `visit_date` FROM `page_impressions` WHERE `session_ID`='".$_SESSION['id']."'";
$visits = mysqli_query($con,$QRYvisits) or die(mysql_error);
$rows = mysqli_num_rows($visits);//assigns value to variable $rows

//query finds total number of visits and executes query
$QRYTotalVisits = "SELECT `visit_ID`, `session_ID`, `IP`, `visit_page`, `visit_date` FROM `page_impressions`";
$totalvisits=mysqli_query($con,$QRYTotalVisits) or die(mysql_error);
$totalrows=mysqli_num_rows($totalvisits);//assigns value to variable $totalrows

$browser_id = $_SERVER['HTTP_USER_AGENT'];//assigns value to the variable 'browser_id' using the '$_SERVER['HTTP_USER_AGENT']' array
if (strpos($browser_id, "Edge"))//checks 'browser_id' for the word 'Edge'
{
  $browser = "Microsoft Edge";
}
else if (strpos($browser_id, "Firefox"))//checks 'browser_id' for the word 'Firefox'
{
  $browser = "Mozilla Firefox";
}
else if (strpos($browser_id, "Chrome"))//checks 'browser_id' for the word 'Chrome'
{
  $browser = "Google Chrome";
}
else //returns unknown as the browser isnt in the 'HTTP_USER_AGENT' array.
{
  $browser = "Unknown";
}
//echo "Browser: $browser";//echos the browser name

if (!isset($_GET['width'])&&(!isset($_GET['height'])))//checks that height and width are set
{
  $page="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//gets page url
  $redirect="
   <script>
   s_width=screen.width;
   s_height=screen.height;
   window.location = '$page'+'?width='+s_width+'&height='+s_height;
   </script>";//adds the screen width and height into the url so it can be accessed using GET
  echo $redirect;
}
else
{
  $s_width = $_GET['width'];//uses GET to get width from url
  $s_height = $_GET['height'];//uses GET to get height from url
  $screen_res = "$s_width x $s_height";//concatenates width and height into a single string
}
$QRYScreenRes = "INSERT into `page_impressions` (`browser_ID`, `screen_res`) VALUES ('".$browser."', '".$screen_res."');";
$ScreenRes = mysqli_query($con,$QRYScreenRes) or die(mysql_error);//writes screen resolution to DB
?>