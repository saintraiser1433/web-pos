<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $tablename = $_POST['table'];
    $key = $_POST['key'];
    $sql = "DELETE FROM $tablename where $key='$id'";
    $conn->query($sql);
}
