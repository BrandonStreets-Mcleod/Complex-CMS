<?php
define('DB_SERVER','localhost');//Server the databse is stored on
define('DB_USERNAME','root');//The database username
define('DB_PASSWORD','');//The database password, howeevr none is set for this database
define('DB_NAME','login_new');//The actual name of the database.

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli === false) {
    die("ERROR: could not connect. " . $mysqli->connect_error);
}
?>
