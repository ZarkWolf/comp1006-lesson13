<?php ob_start();//output buffer to redirect user

//access the current session
session_start();

//remove all session variables
session_unset();

//end the session
session_destroy();

//return home
header('location:default.php');

ob_flush(); ?>