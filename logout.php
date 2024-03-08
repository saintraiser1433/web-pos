<?php
session_start();
session_destroy();

header("Location:index.php");
$_SESSION['response'] = "Successfully Logout";
$_SESSION['type'] = "success";
