<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}

if (isset($_POST['submit'])) {
  $cashierid = $_POST['id'];
  $cashdrawer = $_POST['cashdrawer'];
  $balance = $_POST['balance'];
  $status = 1;
  $dateissued = $_POST['date'];
  $sql = "INSERT INTO sales_reconcilation (cashier_id,cash_drawer,balance,status,date_issued) VALUES ('$cashierid','$cashdrawer','$balance','$status','$dateissued')";
  if ($conn->query($sql)) {
    $_SESSION['response'] = "Success";
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
              <h2 class="page-title">Sales Reconcilation Module</h2>
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
                  <div></div>
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
                          <button class="table-sort" data-sort="sort-name">
                            #
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Cashier Incharge
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Date
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Cash on Drawer
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Cash On Hand
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Total Sales
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Balance
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Status
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Action
                          </button>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="table-tbody">
                      <?php
                      $sql = "SELECT CONCAT(b.first_name, ' ', b.last_name) AS fname,
                      COALESCE(SUM(a.total) + c.amount, SUM(a.total)) AS total,
                      c.amount AS amount,
                      DATE(a.date_transaction) as date,
                      d.status,
                      d.balance,
                      d.cash_drawer,
                      a.cashier_assign
                      FROM sales_payment a
                      LEFT JOIN cashier b ON a.cashier_assign = b.cashier_id
                      LEFT JOIN cashonhand c ON a.cashier_assign = c.cashier_id AND DATE(c.date_added)=DATE(a.date_transaction)
                      LEFT JOIN sales_reconcilation d ON a.cashier_assign = d.cashier_id
                      AND DATE(d.date_issued)=DATE(a.date_transaction)
                      GROUP BY DATE(a.date_transaction),b.cashier_id
                      ORDER BY DATE(a.date_transaction) DESC";
                      $i = 1;
                      $rs = $conn->query($sql);
                      foreach ($rs as $row) { ?>
                        <tr>
                          <td class="sort-id"><?php echo $i++ ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['fname'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['date'] ?></td>
                          <td class="sort-name text-capitalize">₱<?php echo number_format($row['cash_drawer'], 2) ?></td>
                          <td class="sort-name text-capitalize">₱<?php echo number_format($row['amount'], 2) ?></td>
                          <td class="sort-name text-capitalize">₱<?php echo number_format($row['total'], 2) ?></td>
                          <td class="sort-name text-capitalize">₱<?php echo number_format($row['balance'], 2) ?></td>
                          <td style="display:none"><?php echo $row['total'] ?></td>
                          <td style="display:none"><?php echo $row['cashier_assign'] ?></td>
                          <td class="sort-name">
                            <?php
                            if (is_null($row['status'])) {
                              $stat = "";
                              echo '<span class="badge bg-red">Incomplete</span>';
                            } else {
                              $stat = "disabled";
                              echo '<span class="badge bg-success">Complete</span>';
                            }
                            ?>
                          </td>
                          <td>
                            <?php
                            if (is_null($row['status'])) {
                              echo ' <a href="#" class="badge bg-success view">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 4c4.29 0 7.863 2.429 10.665 7.154l.22 .379l.045 .1l.03 .083l.014 .055l.014 .082l.011 .1v.11l-.014 .111a.992 .992 0 0 1 -.026 .11l-.039 .108l-.036 .075l-.016 .03c-2.764 4.836 -6.3 7.38 -10.555 7.499l-.313 .004c-4.396 0 -8.037 -2.549 -10.868 -7.504a1 1 0 0 1 0 -.992c2.831 -4.955 6.472 -7.504 10.868 -7.504zm0 5a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" stroke-width="0" fill="currentColor" />
                              </svg>
                            </a>';
                            } else {
                              echo ' <a href="#" class="badge bg-secondary">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 4c4.29 0 7.863 2.429 10.665 7.154l.22 .379l.045 .1l.03 .083l.014 .055l.014 .082l.011 .1v.11l-.014 .111a.992 .992 0 0 1 -.026 .11l-.039 .108l-.036 .075l-.016 .03c-2.764 4.836 -6.3 7.38 -10.555 7.499l-.313 .004c-4.396 0 -8.037 -2.549 -10.868 -7.504a1 1 0 0 1 0 -.992c2.831 -4.955 6.472 -7.504 10.868 -7.504zm0 5a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" stroke-width="0" fill="currentColor" />
                              </svg>
                            </a>';
                            }
                            ?>

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

  <!-- modals -->
  <div class="modal modal-blur fade" id="modal-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Basic Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <input type="hidden" id="cashierid" name="id">
            <input type="hidden" id="date" name="date">
            <div class="row">
              <div class="col-lg-6">
                <label class="col-form-label">Cashier Name: <span class="text-capitalize namec"></span> </label>
              </div>
              <div class="col-lg-6">
                <label class="col-form-label">Date: <span class="text-capitalize datec"></span></label>
              </div>
              <hr>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱1000x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="1000">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱500x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="500">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱200x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="200">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱100x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="100">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱50x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="50">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱20x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="20">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱10x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="10">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱5x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="5">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱1x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="1">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱0.50x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="0.50">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱0.10x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="0.10">
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3 row">
                  <label class="col-3 col-form-label">₱0.0.5x</label>
                  <div class="col">
                    <input type="number" class="form-control calculation-input" aria-describedby="emailHelp" id="0.05">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <label class="col-form-label">Total Cash On Drawer</label>
                <div class="col">
                  <input type="text" class="form-control" name="cashdrawer" aria-describedby="emailHelp" id="drawers" readonly required>
                </div>
              </div>
              <div class="col-lg-12">
                <label class="col-form-label">Total Income</label>
                <div class="col">
                  <input type="text" class="form-control" aria-describedby="emailHelp" id="income" readonly>
                </div>
              </div>
              <div class="col-lg-12">
                <label class="col-form-label">Total Balance</label>
                <div class="col">
                  <input type="text" class="form-control" name="balance" aria-describedby="emailHelp" id="balance" readonly required>
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

      $(document).on('click', '.view', function() {
        $('#modal-add').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        $('#income').val(data[7]);
        $('#cashierid').val(data[8]);
        $('.namec').html(data[1]);
        $('.datec').html(data[2]);
        $('#cashierid').val(data[8]);
        $('#date').val(data[2]);
        $('.modal-title').html('Sales Reconcilation');

      });



    });
  </script>

  <script>
    $(document).ready(function() {
      // Add keyup event listener to calculation inputs
      $('.calculation-input').keyup(calculate);

      // Function to perform calculation
      function calculate() {
        var total = 0;

        // Loop through each calculation input field
        $('.calculation-input').each(function() {
          var quantity = $(this).val() || 0;
          var value = $(this).attr('id');

          // Multiply quantity by value and update total
          total += quantity * value;
        });

        // Format total with two decimal places
        var formattedTotal = total.toFixed(2);

        // Update the value of the 'drawers' input field
        $('#drawers').val(formattedTotal);
        // Calculate and update the balance
        var income = Number($('#income').val());
        var balance = total - income;
        var formattedTotals = balance.toFixed(2);
        $('#balance').val(formattedTotals);
      }
    });
  </script>
</body>

</html>