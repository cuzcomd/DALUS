<?php
include('config.php');
session_start();
if(!isset($_SESSION['userid'])) {
 header("Location: index.php");
}
//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];
?>


