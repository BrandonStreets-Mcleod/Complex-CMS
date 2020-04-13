<html>
    <?php
    $page_ID="Add post";
    include ('../Website Stats/Website_stats.php');
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    if (!empty($_POST))
    {
        require_once('../includes/class-insert.php');
        if ($insert->post($_POST))
        {
            echo '<p>Data submitted successfully</p>';
        }
    }
    ?>
    <head>
        <title>Insert Post</title>
    </head>
    <body>
        <link rel="stylesheet" href="../stylesheet.css">
        <form action="../index.php">
            <input type="submit" value="Home">
        </form>
        <form method="post">
            <p>
                <label for="post_title">Post Title</label>
                <input type="text" name="post_title"/>
            </p>
            <p>
            <p>
                <label for="post_content">Post Content</label>
                <textarea name="post_content"></textarea>
            </p>
            <p>
                <label for="post_category">Cat 1</label>
                <input type="checkbox" name="post_category[first]" value="cat1"/>
                <br />
                <label for="post_category">Cat 2</label>
                <input type="checkbox" name="post_category[second]" value="cat2"/>
            </p>
            <p>
                <input type="submit" value="Submit"/>
            </p>
        </form>
    </body>
</html>