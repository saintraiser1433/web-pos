<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}
include_once("../dist/libs/phpjasperxml/PHPJasperXML.inc.php");

if (isset($_POST['printdaily'])) {
  $daily = $_POST['daily'];

  $PHPJasperXML = new PHPJasperXML();
  $PHPJasperXML->arrayParameter = array("dates" => $daily);
  $PHPJasperXML->load_xml_file("../static/report/dailystock.jrxml");

  $PHPJasperXML->transferDBtoArray("localhost", "root", "", "sr");
  $PHPJasperXML->outpage("I", "../static/report/output/dailystock.pdf");

  header("Location:../static/report/output/dailystock.pdf");
}



if (isset($_POST['submit'])) {
  $supplier = $_POST['supplier'];
  $itemname = $_POST['itemname'];
  $quantity = $_POST['quantity'];
  $actualquantity = $_POST['actualquantity'];
  $date = $_POST['datedeliver'];

  $sql = "INSERT INTO stock (supplier_id,inventory_id,quantity,later_quantity,date_delivered) VALUES ('$supplier','$itemname','$quantity','$actualquantity','$date')";
  if ($conn->query($sql)) {
    $_SESSION['response'] = "Add stock successfully added";
    $_SESSION['type'] = "success";
    $upsql = "UPDATE inventory SET quantity = quantity + $quantity where inventory_id='$itemname'";
    $conn->query($upsql);
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
              <h2 class="page-title">Stocks Panel</h2>
            </div>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="card">
            <div class="card-header">
              <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                  <a href="#tabs-home-8" class="nav-link active" data-bs-toggle="tab">Stock In</a>
                </li>
                <li class="nav-item">
                  <a href="#tabs-profile-8" class="nav-link" data-bs-toggle="tab">Stock Out</a>
                </li>
              </ul>
            </div>
            <div class="card-status-bottom bg-primary"></div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane fade active show" id="tabs-home-8">

                  <div id="listjs">
                    <div class="d-flex align-items-center justify-content-between">
                      <button type="button" class="btn btn-primary add">Add</button>
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
                            <th style="display:none"></th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Supplier Name
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Item Name
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Later Stocks
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Quantity Stock In
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Date Delivered
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
                          $sql = "SELECT a.stock_id,a.quantity,a.later_quantity,b.item_name,c.supplier_name,a.date_delivered FROM stock a LEFT JOIN inventory b ON a.inventory_id=b.inventory_id LEFT JOIN suppliers c ON a.supplier_id=c.supplier_id order by a.date_delivered ";
                          $rs = $conn->query($sql);
                          foreach ($rs as $row) { ?>
                            <tr>

                              <td class="sort-name text-capitalize"><?php echo $row['supplier_name'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['item_name'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['later_quantity'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['quantity'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['date_delivered'] ?></td>
                              <td style="display:none"><?php echo $row['stock_id'] ?></td>
                              <td>
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
                <div class="tab-pane fade" id="tabs-profile-8">

                  <div id="listjs">
                    <div class="d-flex align-items-center justify-content-between">
                      <div></div>
                      <div class="flex-shrink-0">
                        <form action="" method="post" id="myForm">
                          <div class="input-group mb-2">
                            <input type="date" name="daily" id="daily" class="form-control" required>
                            <button class="btn btn-success" name="dailys" type="submit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                              </svg></button> &nbsp;
                            <button class="btn btn-info" name="printdaily" type="submit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                              </svg></button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <br>
                    <div id="pagination-container"></div>
                    <div id="table-default" class="table-responsive">
                      <table class="table" id="tables">
                        <thead>
                          <tr>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Date
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Item Name
                              </button>
                            </th>

                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Quantity Sold
                              </button>
                            </th>

                          </tr>
                        </thead>
                        <tbody class="table-tbody">
                          <?php
                          if (isset($_POST['dailys'])) {
                            $date = $_POST['daily'];
                          } else {
                            $date = date('Y-m-d');
                          }

                          $sql = "SELECT
                          SUM(a.quantity) AS qty,
                          item_name,
                          description,
                          DATE(b.date_transaction) AS date
                      FROM
                          sales a,
                          sales_payment b
                      WHERE
                          a.invoice_no = b.invoice_no
                          and DATE(b.date_transaction) ='$date'
                      GROUP BY
                          DATE(b.date_transaction),
                          a.item_name";
                          $rs = $conn->query($sql);
                          foreach ($rs as $row) { ?>
                            <tr>

                              <td class="sort-name text-capitalize"><?php echo $row['date'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['item_name'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['qty'] ?></td>


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
        </div>
      </div>
      <?php include '../static/nav/footer.php'; ?>
    </div>
  </div>

  <!-- modals -->
  <div class="modal modal-blur fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Basic Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">Suppliers:</label>
                  <select name="supplier" class="form-select text-capitalize" required>
                    <option value="">-</option>
                    <?php
                    $sql = "SELECT * FROM suppliers where status=0 order by supplier_id";
                    $rs = $conn->query($sql);
                    foreach ($rs as $row) {
                      echo '<option value=' . $row['supplier_id'] . '>' . $row['supplier_name'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Item Name:</label>
                  <select name="itemname" class="form-select text-capitalize" id="itemname" required>
                    <option value="">-</option>
                    <?php
                    $sql = "SELECT * FROM inventory where status=0";
                    $rs = $conn->query($sql);
                    foreach ($rs as $row) {
                      echo '<option value=' . $row['inventory_id'] . '>' . $row['item_name'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Actual Quantity:</label>
                  <input type="text" class="form-control" name="actualquantity" id="actualquantity" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label">Enter Quantity:</label>
                  <input type="text" class="form-control" name="quantity" id="quantity" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Date Delivered:</label>
                  <input type="date" class="form-control" name="datedeliver" id="datedeliver" required>
                </div>
              </div>

            </div>

          </div>

          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary ms-auto" name="submit">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 5l0 14" />
                <path d="M5 12l14 0" />
              </svg>
              Save
            </button>
          </div>
        </form>
      </div>
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

      $('#firstname, #lastname').on('keyup', function() {
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();

        var result = "";

        if (lastname.length > 0) {
          result += lastname + "_";
        }

        if (firstname.length > 0) {
          result += firstname.substring(0, 3);
        }

        $('#username').val(result);
      });


      $('#itemname').on('change', function() {
        var a = $(this).val();
        $.ajax({
          method: "POST",
          url: "../static/ajax/quantity.php",
          data: {
            myids: a,
          },
          success: function(html) {
            $('#actualquantity').val(html);
          }

        });

      });


      $(document).on('click', '.add', function() {
        $('#modal-add').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        $('.modal-title').html('Add Stocks');
        $('#id').val('');


      });



      $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        var currentRow = $(this).closest("tr");
        var col1 = currentRow.find("td:eq(5)").text();
        swal({
            title: "Are you sure?",
            text: "Once deleted, the quantity will be minus in the inventory.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                method: "POST",
                url: "../static/ajax/deletestock.php",
                data: {
                  myids: col1
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