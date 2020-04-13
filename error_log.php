<?php
function myException($exception)
{
    echo "<b>An error has occured!</b>";
    error_log("Error: ".$exception->getMessage()." IP: ".$_SERVER['REMOTE_ADDR']."\n",3,"err.log");
}
set_exception_handler('myException');
?>