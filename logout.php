<?php
session_start();
// Unset all session variables
$_SESSION = array();
// Destroy the session
session_destroy();
// Redirect to welcome page
header("Location: welcomepage.php");
exit;
?>
