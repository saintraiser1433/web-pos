<?php
include '../../connection.php';

$cashierid = $_SESSION['cashier_id'];
$sql = "DELETE FROM cart where cashier_id='$cashierid'";
$conn->query($sql);
$_SESSION['discounts'] = 0.00;
