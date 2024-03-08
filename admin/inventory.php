<?php
include '../connection.php';
include '../dist/libs/qr/qrlib.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}



if (isset($_POST['submit'])) {
  $assetcode = $_POST['assetcode'];
  $itemname = $_POST['itemname'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $quantity = $_POST['quantity'];
  $condition = $_POST['price'];
  $photo = $itemname . time();
  $price = $_POST['price'];
  if (isset($_POST['status'])) {
    $availability = 0;
  } else {
    $availability = 1;
  }

  $fileName = $itemname . ".png";
  foreach ($_FILES['files']['name'] as $key => $val) {
  }
  if ($_FILES['files']['tmp_name'][$key] == '') {
    $dirs = '../static/images/no-image.png';
  } else {
    $tmpname = $_FILES['files']['tmp_name'][$key];
    $dir = "../static/productimage/";
    $dirs = "../static/productimage/$photo.png";
    move_uploaded_file($tmpname, $dir . '/' . $photo . '.png');
  }

  $sql = "INSERT INTO inventory (inventory_id,category_id,description,item_name, quantity,price, status,photo) VALUES ('$assetcode', '$category', '$description', '$itemname', '$quantity', '$price','$availability','$dirs')";
  if ($conn->query($sql)) {
    $_SESSION['response'] = "Item successfully added";
    $_SESSION['type'] = "success";
    $pngAbsoluteFilePath = '../static/itemcode/' . $fileName;
    QRcode::png($assetcode, $pngAbsoluteFilePath);
  } else {
    $_SESSION['response'] = "Error";
    $_SESSION['type'] = "error";
  }
}


if (isset($_POST['update'])) {
  $assetcode = $_POST['assetcode'];
  $itemname = $_POST['itemname'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $condition = $_POST['price'];
  $photo = $itemname . time();
  $price = $_POST['price'];
  if (isset($_POST['status'])) {
    $availability = 0;
  } else {
    $availability = 1;
  }

  $fileName = $itemname . ".png";
  foreach ($_FILES['files']['name'] as $key => $val) {
  }
  if ($_FILES['files']['tmp_name'][$key] == '') {
    $sql = "UPDATE inventory SET inventory_id='$assetcode',category_id='$category',description='$description',item_name='$itemname',price='$price',status='$availability' WHERE inventory_id='$assetcode'";
  } else {
    $tmpname = $_FILES['files']['tmp_name'][$key];
    $dir = "../static/productimage/";
    $dirs = "../static/productimage/$photo.png";
    move_uploaded_file($tmpname, $dir . '/' . $photo . '.png');
    $sql = "UPDATE inventory SET inventory_id='$assetcode',category_id='$category',description='$description',item_name='$itemname',price='$price',photo='$dirs',status='$availability' WHERE inventory_id='$assetcode'";
  }



  if ($conn->query($sql)) {
    $_SESSION['response'] = "Item successfully updated";
    $_SESSION['type'] = "success";
  } else {
    $_SESSION['response'] = "Error";
    $_SESSION['type'] = "error";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include '../static/nav/head.php' ?>

<body>
  <script src="../dist/js/demo-theme.min.js?1684106062"></script>
  <div class="page">
    <!-- Navbar -->
    <?php include '../static/nav/topbar.php' ?>
    <?php include '../static/nav/navbar.php' ?>
    <div class="page-wrapper">
      <!-- Page header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">Inventory List</h2>
            </div>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="card">
            <div class="card-status-bottom bg-primary"></div>

            <div class="card-body">
              <div id="listjs">
                <div class="d-flex align-items-center justify-content-between">
                  <a href="ae-inventory.php"><button type="button" class="btn btn-primary add">Add</button></a>
                  <div class="flex-shrink-0">
                    <input class="form-control listjs-search" id="search-input" placeholder="Search" style="max-width: 200px;" />
                  </div>
                </div>
                <br>
                <div id="pagination-container"></div>
                <div id="table-default" class="table-responsive">
                  <table class="table" id="tables">
                    <thead>
                      <tr>
                        <th>
                          <button class="table-sort" data-sort="sort-id">
                            Asset Code
                          </button>
                        </th>
                        <th>Img</th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Asset Name
                          </button>
                        </th>

                        <th>
                          <button class="table-sort" data-sort="sort-gender">
                            Description
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-dob">
                            Category
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-gender">
                            Quantity
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-contact">
                            Price
                          </button>
                        </th>

                        <th>
                          <button class="table-sort" data-sort="sort-status">
                            Status
                          </button>
                        </th>
                        <th>
                          <button class="table-sort">
                            Action
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="table-tbody">
                      <?php
                      $sql = "SELECT a.*,b.description as catdes FROM inventory a,category b where a.category_id=b.category_id order by a.category_id";
                      $rs = $conn->query($sql);
                      foreach ($rs as $row) { ?>
                        <tr>
                          <td class="sort-id w-0"><?php echo $row['inventory_id'] ?></td>
                          <td><img src="<?php echo $row['photo']; ?>" class="rounded-circle" style="width:30px;height:30px;"></td>
                          <td class="sort-name text-capitalize"><?php echo $row['item_name'] ?></td>
                          <td class="sort-gender w-25"><?php echo $row['description'] ?></td>
                          <td class="sort-gender"><?php echo $row['catdes'] ?></td>
                          <td class="sort-dob text-capitalize">
                            <?php if ($row['quantity'] === '0') {
                              echo "<span class='text-danger'>Out of Stock</span>";
                            } else {
                              echo $row['quantity'];
                            }
                            ?></td>
                          <td class="sort-contact">₱ <?php echo number_format($row['price'], 2, '.', ',') ?></td>
                          <td>
                            <?php
                            if ($row['status'] === '0') {
                              echo '<span class="badge bg-success">Available</span>';
                            } else {
                              echo '<span class="badge bg-red">Not Available</span>';
                            }

                            ?>
                          </td>
                          <td>
                            <a href="ae-inventory.php?ic=<?php echo $row['inventory_id'] ?>" class="badge bg-yellow">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" />
                                <path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" />
                              </svg>

                            </a> |
                            <a href="#" class="badge bg-red delete">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7h16" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                <path d="M10 12l4 4m0 -4l-4 4" />
                              </svg>
                            </a>
                          </td>

                        </tr>
                      <?php  } ?>

                    </tbody>
                  </table>
                  <br>
                  <div class="btn-toolbar">
                    <p class="mb-0" id="listjs-showing-items-label">Showing 0 items</p>
                    <ul class="pagination ms-auto mb-0"></ul>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include '../static/nav/footer.php'; ?>
    </div>
  </div>




  <?php include '../static/nav/scripts.php' ?>



  <?php
  if (isset($_SESSION['response']) && $_SESSION['response'] != "") {

  ?>
    <script>
      swal({
        title: "<?php echo $_SESSION['response']; ?>",
        icon: "<?php echo $_SESSION['type']; ?>",
        button: "Exit!",
      })
    </script>
  <?php unset($_SESSION['response']);
  }
  ?>


  <script>
    $(document).ready(function() {


      $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var currentRow = $(this).closest("tr");
        var col1 = currentRow.find("td:eq(0)").text();
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                method: "POST",
                url: "../static/ajax/delete.php",
                data: {
                  myids: col1,
                  table: 'inventory',
                  key: 'inventory_id'
                },
                success: function(html) {
                  swal("Poof! Your imaginary file has been deleted!", {
                    icon: "success",
                  }).then((value) => {
                    location.reload();
                  });
                }

              });

            } else {
              swal("Your imaginary file is safe!");
            }
          });
      });
    });
  </script>
</body>

</html>