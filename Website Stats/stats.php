<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Statistics</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
    <body>
        <a href="index.php">Homepage</a>
        <a href="logout.php">Logout</a>
        <div class="row">
          <div class="col-12 col-md-6">
              <?php
              session_start();//starts session
              $w = 0;
              $z = 0;
              $y = 0;
              $t = 0;
              $page_ID="stats";
              if (!isset($_SESSION['id']))//checks if session id has been set and if not then assigns session id to session
              {
                  $_SESSION['id']=session_id();
              }

              $con = mysqli_connect("localhost","root","","sitestats");//allows for DB connection 
              if (mysqli_connect_errno())//returns error message if DB cannot be connected to
              {
                  echo "Failed to connect to MYSQL: " . mysqli_connect_error();//error message
              }
              else
              {
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
              $result = mysqli_query($con,$query);//executes query to store session ID, IP, visit_page and prev_page

              //PAGE VISIT STORE
              $QRYvisits = "SELECT `visit_ID`, `session_ID`, `IP`, `visit_page`, `visit_date` FROM `page_impressions` WHERE `session_ID`='".$_SESSION['id']."'";
              $visits = mysqli_query($con,$QRYvisits) or die(mysql_error());//executes query
              $rows = mysqli_num_rows($visits);
              echo "<h1>Visits in this session</h1>";
              echo "You have loaded <b>".$rows."</b> pages on this visit<br><br>";//echos out the number of pages loaded on visit

              //TOTAL VISITS STORE
              $QRYTotalVisits = "SELECT `visit_ID`, `session_ID`, `IP`, `visit_page`, `visit_date` FROM `page_impressions`";
              $totalvisits=mysqli_query($con,$QRYTotalVisits) or die(mysql_error);
              echo "<h1>Total Visits</h1>";
              $totalrows=mysqli_num_rows($totalvisits);//uses mysqli_num_rows to find total number of pages loaded
              echo "You have loaded <b>".$totalrows."</b> pages in total<br><br>";//echos out result

              //UNIQUE HITS STORE
              $QRYUniqueHits = "SELECT `visit_ID`, COUNT(DISTINCT(session_ID)) FROM `page_impressions` GROUP BY `session_ID`";
              $uniquehits = mysqli_query($con,$QRYUniqueHits) or die(mysql_error);//executes query to find number of unique hits to site
              $unique_rows = mysqli_num_rows($uniquehits);//finds number of rows that are unique sessions
              $QRYAllPage = "SELECT `visit_page` FROM `page_impressions`";//query to get all occurences of visit_page
              $allpage = mysqli_query($con,$QRYAllPage) or die(mysql_error);//executes query
              foreach ($allpage as $page_name)//creates an array with all page names in
              {//IF ELSEIF statements below count up each occurences of each page name
                  if (count(array_keys($page_name, "homepage")) == 1)
                  {
                      $w++;
                  }
                  else if (count(array_keys($page_name, "link1")) == 1)
                  {
                      $z++;
                  }
                  else if (count(array_keys($page_name, "link2")) == 1)
                  {
                      $y++;
                  }
                  else if (count(array_keys($page_name, "stats")) == 1)
                  {
                      $t++;
                  }
              }
              $page_count = array($w,$z,$z,$t);//puts all values from counter into an array
              $PageNames = array("homepage","link1","link2","stats");//puts all page names into an array
              $page_array = array_combine($PageNames,$page_count);//combines the arrays $page_count and $PageNames
              $MostPop = implode(array_keys($page_array,max($page_array)));//uses function max() to find most popular page
              //PAGES VISITED PAST 7 DAYS
              $curr_date = date('Y-m-d', strtotime('+1 days'));//gets current date plus one day
              $today_date = date('Y-m-d');//gets todays date
              $end_date = date('Y-m-d', strtotime('-7 days'));//gets the date a week ago
              $QRYPage7Days = "SELECT `visit_ID`, `visit_date` FROM `page_impressions` WHERE `visit_date` BETWEEN '".$end_date."' AND '".$curr_date."'";
              $page7days = mysqli_query($con,$QRYPage7Days) or die(mysql_error);//executes query
              $page_count = 0;
              foreach ($page7days as $single_page)//foreach to count number of pages access within the past week
              {
                  $page_count++;//increments variable $page_count
              }

              //OUTPUT ALL VISIT TEXT
              echo "<h1>Total Unique Visits</h1>";
              echo "You have had <b>".$unique_rows."</b> unique visiters access your pages<br>";//echos out all data gathered from above code
              echo "The homepage has had <b>".$w."</b> visitors<br>";//echos out all data gathered from above code
              echo "The 1st link page has had <b>".$z."</b> visitors<br>";//echos out all data gathered from above code
              echo "The 2nd link page has had <b>".$y."</b> visitors<br>";//echos out all data gathered from above code
              echo "The stats page has had <b>".$t."</b> visitors<br>";//echos out all data gathered from above code
              echo "The most visited page is <b>".$MostPop."</b><br>";//echos out all data gathered from above code
              echo "The number of pages visited between ".$end_date." and ".$today_date." is <b>".$page_count."</b><br><br>";//echos out all data gathered from above code

              //AVERAGE TIME STORE
              $QRYAvgTime = "SELECT `session_ID`, `visit_date` FROM `page_impressions` WHERE `session_ID` = '".$_SESSION['id']."' ORDER BY `visit_date`";
              $alltime = mysqli_query($con,$QRYAvgTime) or die(mysql_error);//executes query to find session ID and date from a specific session ID 
              $alltimerows=mysqli_num_rows($alltime);//counts number of rows returned from query
              $i=0;
              foreach ($alltime as $alltime_item)//foreach loop to find time from each row
              {
                  $calctime[$i]['time']=$alltime_item['visit_date'];
                  $i++;
              }
              $time1 = abs(strtotime($calctime[sizeof($calctime)-1]['time']) - time()) / 60;//calculates time 
              $time2 = abs(strtotime($calctime[0]['time']) - time()) / 60;//calculates time
              $total_time = round($time2 - $time1, 0, PHP_ROUND_HALF_UP);//calculates total time the user has been on the site
              echo "<h1>Average time on site</h1>";
              echo "The session ".$_SESSION['id']." has spent <b>".$total_time."</b> minutes on the site<br><br>";
              echo "<h1>Average number of pages visited</h1>";
              $avgpagesvisited = round($totalrows/$unique_rows, 0, PHP_ROUND_HALF_UP);//finds the average number of pages visited
              echo "The average number of pages visited is: <b>".$avgpagesvisited."</b><br><br>";
              ?>
          </div>

          <div class="col-12 col-md-6">
              <?php
              //USER BROWSER STORAGE
              echo "<h1>User Info</h1>";
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
              echo "Browser: $browser";//echos the browser name
              ?>

              <script>
                  document.write("<br>Your screen resolution is: " + screen.width + "x" + screen.height);//outputs user screen resolution
              </script>
              <?php
              //SCREEN RES STORE
              if (!isset($_GET['width'])&&(!isset($_GET['height'])))//checks if the screen width and height are set
              {
                  $page="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//assigns the variable 'page' to the page url
                  $redirect="
                   <script>
                   s_width=screen.width;
                   s_height=screen.height;
                   window.location = '$page'+'?width='+s_width+'&height='+s_height;
                   </script>";//puts the screen width and height into the url
                  echo $redirect;
              }
              else
              {
                  $s_width = $_GET['width'];//gets width from url
                  $s_height = $_GET['height'];//gets height from url
                  $screen_res = "$s_width x $s_height";//assigns value to variable 'screen_res'
              }
              $QRYScreenRes = "INSERT into `page_impressions` (`browser_ID`, `screen_res`) VALUES ('".$browser."', '".$screen_res."');";
              //creates query to insert screen_res into a database
              $ScreenRes = mysqli_query($con,$QRYScreenRes) or die(mysql_error);//executes query to insert into database

              //IP AND USER INFO STORE
              $QRYIPAddress = "SELECT `IP` FROM `page_impressions` WHERE `session_ID`='".$_SESSION['id']."'";
              $IPAddress = mysqli_query($con,$QRYIPAddress) or die(mysql_error);
              //$IPAddress = intval($IPAddress);//need to find a single IP address as pulls all of them from database
              //echo "<br>IP Address: $IPAddress";
              $OS = php_uname('s');
              $Processor = php_uname('m');
              echo "<br>The session ".$_SESSION['id']." is using a ".$OS." operating system with a ".$Processor." processor.<br><br>";

              //creates query for peak date chart
              echo "<h1>Peak Times and dates</h1>";
              $QRYDateTime = "SELECT COUNT(DISTINCT(visit_date)) AS count, `visit_date` FROM `page_impressions` GROUP BY `visit_ID`";
              $DateTime = mysqli_query($con,$QRYDateTime) or die(mysql_error);
              $date = date('d-m-Y');
              $adder = 0;
              $Past7Days = array();
              while ($date != $end_date)//checks if all 7 days have been checked
              {
                $date = date('Y-m-d', strtotime('-'.$adder. 'days'));//assigns the date to be checked to a variable
                $Past7Days[$date] = 0;//sets each dates value to 0
                $adder++;//increments $adder to allow for each date to be checked
              }
              while($row = mysqli_fetch_array($DateTime))//only breaks into this on the first iteration NEED TO FIX 
              {
                  $all_date = explode(" ", $row['visit_date']);//splits datetime into array with date and time seperate
                  $arraykeys = array_keys($Past7Days);//creates an array of each key from the array $Past7Days
                  for ($i = 0; $i < 7; $i++) {//iterates through the variable $i, this allows for it to be used as an array key
                      if ($arraykeys[$i] == $all_date[0]) {//checks if $arraykeys[$i] is equal to $all_date[0] 
                          $Past7Days[$arraykeys[$i]]++;//increments the value of the array segment referenced
                       }
                   }
              }
              ?>
              <script type="text/javascript">
                  google.charts.load('current', {'packages':['corechart']});
                  google.charts.setOnLoadCallback(drawChart);

                  function drawChart()//function to draw chart
                  {
                      var data = google.visualization.arrayToDataTable([
                          ['Dates', 'Visitors'],
                          <?php

                          for ($a = 6; $a >= 0; $a--) {//for loop to echo out each chart record
                              echo "['".$arraykeys[$a]."',".$Past7Days[$arraykeys[$a]]."],";
                          }
                          ?>
                      ]);

                      var options = {//sets chart options 
                          title: 'Peak Visit times',
                          curveType: 'none',
                          legend: { position: 'bottom' }
                      };

                      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                      chart.draw(data, options);//draws the chart
                  }
              </script>
              <div id="curve_chart" style="width: 600px; height: 375px"></div><!--sets chart size and ID-->
          </div>
        </div>
    </body>
</html>