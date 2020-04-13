<?php
$page_ID="homepage";
include ('Website Stats/Website_stats.php');
require_once('includes/class-query.php');
require_once('includes/class-db.php');
include_once('error_log.php');
$mysqli = new mysqli('localhost','root','','cman');
$edit = 0;
if (!empty($_GET))
    {
    if (!empty($_GET['p']))
        {
        $post = $_GET['p'];
        }
    if (!empty($_GET['cat']))
        {
        $cat = $_GET['cat'];
        }
    }

//Checked

    if (empty($post) && empty($cat))
    {
    $post_array = $query->all_posts();
    }
    elseif (!empty($post))
    {
    $post_array = $query->post($post);
    }
    elseif (!empty($cat))
    {
        echo 'cat';
    }

//Checked
?>
<html>
    <head>
        <title>Content Management System</title>
    </head>
    <body>
        <link rel="stylesheet" href="stylesheet.css">
        <form action="redirect.php">
            <input type="submit" name="Edit" value="Edit">
        </form>
        <form action="admin/post-edit.php">
            <input type="submit" name="New Post" value="New Post">
        </form>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label>Search</label>
            <input type="text" name="search">
            <input type="submit" value="Search">
        </form>
        <h1>Blog System</h1>
        <?php
        if (isset ($_POST['search']) && !empty($_POST['search']))
        {
            $search_filter = $_POST['search'];//compare this to post_title to get specific posts
            $QRYSearch = "SELECT `ID`, `post_title`, `post_content`, `post_category` FROM `posts` WHERE `post_title` = '$search_filter'";
            $Search = mysqli_query($mysqli, $QRYSearch) or die(mysql_error);
            foreach ($Search as $search_field)
            {
                if (isset($_SESSION['editmode']) && $_SESSION['editmode'] == 0)
                {
                 ?>
                    <div class='post'>
                        <h1><a href="?p=<?php echo $search_field['ID'];?>"><?php echo $search_field['post_title']; ?></a></h1>
                        <p><?php echo $search_field['post_content']; ?></p>
                    </div>
                <?php
                }
                else
                {
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && $_SESSION['editmode'] == 1)
                    {
                        echo "<form action='logout.php'>
                    <input type='submit' name='logout' value='Logout'>
                    </form>";
                    echo "<div class='post'>";
                    echo "<form name='".$search_field['ID']."' action='edit_post.php'>";
                    echo "<h1><a href='".$search_field['ID']."'></a></h1>";
                    echo "<textarea name='content[".$search_field['ID']."]' rows='5' cols='85'>".$search_field['post_content']."</textarea>";
                    echo "<br>";
                    echo "<input type='submit' value='Update'>"; 
                    echo "</form>";
                    echo "<form action='delete_post.php' method='get'>";
                    echo "<input type='submit' name='".$search_field['ID']."' value='delete'>";
                    echo "</form>";
                    }
                }
            }
        }
        else
        {
            if (isset($_SESSION['editmode']) && $_SESSION['editmode'] == 0)
            {
            foreach ($post_array as $post): 
            ?>
            <div class='post'>
                <h1><a href="?p=<?php echo $post->ID;?>"><?php echo $post->post_title; ?></a></h1>
                <p><?php echo $post->post_content ?></p>
            </div>
            <?php endforeach;
            }
                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true && $_SESSION['editmode'] == 1)
                {
                    echo "<form action='logout.php'>
                    <input type='submit' name='logout' value='Logout'>
                    </form>";
                foreach ($post_array as $post):
                echo "<div class='post'>";
                echo "<form name='".$post->ID."' action='edit_post.php'>";
                echo "<h1><a href='".$post->ID."'></a></h1>";
                echo "<textarea name='content[".$post->ID."]' rows='5' cols='85'>".$post->post_content."</textarea>";
                echo "<br>";
                echo "<input type='submit' value='Update'>"; 
                echo "</form>";
                echo "<form action='delete_post.php' method='get'>";
                echo "<input type='submit' name='$post->ID' value='Delete'>";
                echo "</form>";
                echo "</div>";
                endforeach;
                }
        }
        ?>
    </body>
</html>