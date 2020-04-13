<?php
    include_once('error_log.php');
    session_start();
    $editmode = $_SESSION['editmode'];
    echo $editmode;
    if ($editmode == 0) {
        echo $editmode;
        if ($_SESSION["loggedin"] === true)
        {
            $_SESSION['editmode'] = 1;
            header("location: index.php");
        }
        else
        {
            $_SESSION['editmode'] = 0;
            header("location: login.php");
        }
    } elseif ($editmode == 1) {
        $_SESSION['editmode'] = 0;
        header("location: index.php");
    }
?>
