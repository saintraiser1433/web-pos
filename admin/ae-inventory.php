<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}


if (isset($_GET['ic'])) {
  $id = $_GET['ic'];
  $sql = "SELECT * FROM inventory where inventory_id='$id'";
  $rs = $conn->query($sql);
  $row = $rs->fetch_assoc();
  $itemcode = $row['inventory_id'];
  $category = $row['category_id'];
  $description = $row['description'];
  $itemname = $row['item_name'];
  $quantity = $row['quantity'];
  $price = $row['price'];
  $status = $row['status'] === '0' ? 'checked' : '';
  $photo = $row['photo'];
  $isUpdate = true;
} else {
  $itemcode = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 6);
  $category = "";
  $description = "";
  $itemname = "";
  $quantity = "";
  $price = "";
  $status = "";
  $photo = "../static/images/no-image.png";
  $isUpdate = false;
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
              <h2 class="page-title">Add Item</h2>
            </div>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <form action="inventory.php" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Thumbnail</h3>
                  </div>
                  <div class="card-body">
                    <center>
                      <img id="ImgID" src="" width="230px" height="230px" style="max-height:230px; max-width:230px; min-width:230px; min-height:230px; border:2px solid gray">
                    </center><br>
                    <input type="file" name="files[]" id="filer_input_single" class="form-control" onchange="readURL(this);" />
                    </center>
                  </div>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between">

                    <h3 class="card-title">Basic Information</h3>
                    <div class="flex-shrink-0">
                      <a href="inventory.php"><button type="button" class="btn btn-primary add">Back</button></a>
                    </div>

                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="mb-3">
                          <label class="form-label">Item Code</label>
                          <input type="text" class="form-control" name="assetcode" id="assetcode" value="<?php echo $itemcode ?>" required readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label class="form-label">Item Name</label>
                          <input type="text" class="form-control" name="itemname" id="itemname" value="<?php echo $itemname ?>" required>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label class="form-label">Category</label>
                          <select class="form-select text-capitalize" name="category" id="category" value="<?php echo $category ?>" required>
                            <option value="" selected>-</option>
                            <?php
                            $sql = "SELECT * FROM category order by category_id asc";
                            $rs = $conn->query($sql);
                            foreach ($rs as $row) {
                            ?>
                              <option value="<?php echo $row['category_id'] ?>"><?php echo $row['description'] ?></option>
                            <?php } ?>


                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="mb-3">
                          <label class="form-label">Item Description</label>
                          <textarea class="form-control" name="description" rows="4" id="description"><?php echo $description ?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <?php
                      if (isset($_GET['ic'])) { ?>
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="price" value="<?php echo $price ?>" onkeypress="return isNumberKey(event)">
                          </div>
                        </div>
                      <?php } else { ?>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="price" id="price" value="<?php echo $price ?>" onkeypress="return isNumberKey(event)">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity">
                          </div>
                        </div>

                      <?php } ?>
                      <div class="row"></div>
                      <div class="col-lg-12">
                        <div class="mb-3">
                          <label class="form-check">
                            <?php
                            if ($status)
                            ?>
                            <input class="form-check-input" type="checkbox" name="status" <?php echo $status ?>>
                            <span class="form-check-label">Available</span>
                          </label>
                        </div>
                      </div>

                      <?php
                      if ($isUpdate) {
                        echo '<button type="submit" class="btn btn-primary ms-auto" name="update">
                      <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                      </svg>
                      Update
                    </button>';
                      } else {
                        echo '<button type="submit" class="btn btn-primary ms-auto" name="submit">
                      <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                      </svg>
                      Save
                    </button>';
                      }

                      ?>

                    </div>
                  </div>

                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
      <?php include '../static/nav/footer.php'; ?>
    </div>
  </div>

  <!-- modals -->


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
    function isNumberKey(event) {
      var charCode = (event.which) ? event.which : event.keyCode;

      // Allow only digits (0-9) and a single dot (.)
      if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
        return false;
      }

      // Allow only one dot (.)
      if (charCode === 46 && event.target.value.includes('.')) {
        return false;
      }

      return true;
    }

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#ImgID').attr('src', e.target.result);

        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    $(document).ready(function() {

      $('#category').val('<?php echo $category ?>');
      $('#ImgID').attr('src', '<?php echo $photo ?>');

    });
  </script>
</body>

</html>