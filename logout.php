<?php
//logout funtie
session_start();

$_SESSION = ['Gebruiksnaam'];
$_SESSION = ['Medewerkerid'];

session_destroy();

header('location:login.php');

exit;

?>
