<?php
include '../../connection.php';




if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "SELECT quantity from inventory where inventory_id='$id'";
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();
    if ($rs->num_rows > 0) {
        echo $row['quantity'];
    } else {
        echo "";
    }
}
