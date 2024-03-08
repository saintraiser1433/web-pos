<?php
include '../../connection.php';




if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sqlt = "SELECT quantity FROM inventory where inventory_id='$id' and status=0";
    $rs = $conn->query($sqlt);
    $rowq = $rs->fetch_assoc();
    if ($rs->num_rows > 0) {
        echo $rowq['quantity'];
    } else {
        echo "no";
    }
}
