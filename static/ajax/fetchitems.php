<?php
include '../../connection.php';




$searchTerm = $_GET['term'];

// Get matched data from the database 
$query = "SELECT inventory_id, item_name,photo,quantity FROM inventory WHERE (item_name LIKE '%" . $searchTerm . "%' OR inventory_id LIKE '%" . $searchTerm . "%') AND status = 0 ORDER BY inventory_id ASC";
$result = $conn->query($query);

// Generate array with user's data 
$userData = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['item_name'];
        $data['id']    = $row['inventory_id'];
        $data['qty']    = $row['quantity'];
        $data['value'] = $name;
        $data['label'] = ' 
            <a href="javascript:void(0);"> 
                <img src="' . $row['photo'] . '" width="50" height="50"/> 
                <span class="text-capitalize">' . $name . '</span> 
            </a> 
        ';
        array_push($userData, $data);
    }
}

// Return results as json encoded array 
echo json_encode($userData);
