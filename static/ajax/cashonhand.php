<?php
include '../../connection.php';




if (isset($_POST['cashier'])) {
    $id = $_POST['cashier'];
    $amount = $_POST['cash'];
    $date = $_POST['date'];
    $sqlt = "SELECT * FROM cashonhand a,sales_reconcilation b where a.cashier_id=b.cashier_id and date(a.date_added)=date(b.date_issued)";
    $rs = $conn->query($sqlt);
    if ($rs->num_rows > 0) {
        echo '1';
    } else {
        $sql = "INSERT INTO cashonhand (cashier_id,amount,date_added) VALUES ('$id','$amount','$date')";
        $conn->query($sql);
    }
}
