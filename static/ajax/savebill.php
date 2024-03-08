<?php

include '../../connection.php';
include_once("../../dist/libs/phpjasperxml/PHPJasperXML.inc.php");
if (isset($_POST['cashtendered'])) {
    $sqlinvoice = "SELECT COUNT(*) as cnt from sales_payment";
    $rs = $conn->query($sqlinvoice);
    $row = $rs->fetch_assoc();
    $inv = $row['cnt'] + 1;
    $finainv = 'INV-' . str_pad($inv, 5, '0', STR_PAD_LEFT);
    $cash = $_POST['cashtendered'];
    $total = $_POST['grandtotal'];
    $subtotal = $_POST['subtotal'];
    $initialdis =  $_SESSION['discounts'] * 100;
    $discount = $initialdis . "%";
    $change = $_POST['change'];
    $cashierid = $_SESSION['cashier_id'];
    $sqlminusqty = "UPDATE inventory a INNER JOIN cart b ON a.inventory_id = b.inventory_id SET a.quantity = a.quantity - b.quantity";
    $conn->query($sqlminusqty);

    $sqltx = "INSERT INTO sales_payment (invoice_no,cash_tendered,subtotal,total,discount,change_payment,cashier_assign) values ('$finainv','$cash','$subtotal','$total','$discount','$change','$cashierid')";
    $conn->query($sqltx);

    $sql = "INSERT INTO sales (invoice_no,item_name,description,quantity,total_price,base_price) SELECT '$finainv', a.item_name,a.description,b.quantity,b.quantity * a.price,a.price from inventory a,cart b where a.inventory_id=b.inventory_id";
    $conn->query($sql);

    $sqlflush = "DELETE FROM cart";
    $conn->query($sqlflush);
    $_SESSION['discounts'] = 0.00;


    $PHPJasperXML = new PHPJasperXML();
    $PHPJasperXML->arrayParameter = array("invoice" => $finainv);
    $PHPJasperXML->load_xml_file("../report/receipt.jrxml");

    $PHPJasperXML->transferDBtoArray("localhost", "root", "", "sr");
    $PHPJasperXML->outpage("F", "../report/output/receipt.pdf");
}
