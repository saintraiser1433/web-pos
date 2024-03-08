<?php
include '../../connection.php';




if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $st = "SELECT * FROM stock where stock_id='$id'";
    $rs = $conn->query($st);
    $row = $rs->fetch_assoc();
    $qty = $row['quantity'];
    $invid = $row['inventory_id'];
    $sql = "UPDATE inventory SET quantity= quantity - $qty where inventory_id='$invid'";
    $conn->query($sql);
    $sqld = "DELETE FROM stock where stock_id='$id'";
    $conn->query($sqld);
}
