<?php
include '../connection.php';
include_once("../dist/libs/phpjasperxml/PHPJasperXML.inc.php");
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}


if (isset($_POST['printdaily'])) {
  $daily = $_POST['daily'];
  $PHPJasperXML = new PHPJasperXML();
  $PHPJasperXML->arrayParameter = array("dates" => $daily);
  $PHPJasperXML->load_xml_file("../static/report/dailysales.jrxml");

  $PHPJasperXML->transferDBtoArray("localhost", "root", "", "sr");
  $PHPJasperXML->outpage("I", "../static/report/output/daily.pdf");

  header("Location:../static/report/output/dailysales.pdf");
}


if (isset($_POST['printmonthly'])) {
  $monthly = $_POST['monthly'];
  $PHPJasperXML = new PHPJasperXML();
  $PHPJasperXML->arrayParameter = array("date" => $monthly);
  $PHPJasperXML->load_xml_file("../static/report/monthlysales.jrxml");

  $PHPJasperXML->transferDBtoArray("localhost", "root", "", "sr");
  $PHPJasperXML->outpage("I", "../static/report/output/monthlysales.pdf");

  header("Location:../static/report/output/monthlysales.pdf");
}
if (isset($_POST['printyear'])) {
  $PHPJasperXML = new PHPJasperXML();
  $PHPJasperXML->load_xml_file("../static/report/yearly.jrxml");

  $PHPJasperXML->transferDBtoArray("localhost", "root", "", "sr");
  $PHPJasperXML->outpage("I", "../static/report/output/yearlysales.pdf");

  header("Location:../static/report/output/yearlysales.pdf");
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
              <h2 class="page-title">Sales Report Module</h2>
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
                  <a href="#tabs-home-8" class="nav-link active" data-bs-toggle="tab">Daily Report</a>
                </li>
                <li class="nav-item">
                  <a href="#tabs-profile-8" class="nav-link" data-bs-toggle="tab">Monthly Report</a>
                </li>
                <li class="nav-item">
                  <a href="#tabs-activity-8" class="nav-link" data-bs-toggle="tab">Yearly Report</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane fade active show" id="tabs-home-8">
                  <div id="listjs">
                    <div class="d-flex align-items-center justify-content-between">
                      <div></div>
                      <div class="flex-shrink-0">
                        <form action="" method="post" id="myForm">
                          <div class="input-group mb-2">
                            <input type="date" name="daily" id="daily" class="form-control" required>
                            <button class="btn btn-success" name="searchs" type="submit">
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
                                Invoice Number
                              </button>
                            </th>
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
                                Quantity
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Discount
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Total
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Cashier Incharge
                              </button>
                            </th>
                          </tr>
                        </thead>
                        <tbody class="table-tbody">
                          <?php
                          if (isset($_POST['searchs'])) {
                            $date = $_POST['daily'];
                          } else {
                            $date = date('Y-m-d');
                          }

                          $sql = "SELECT
                          a.invoice_no,
                          b.discount,
                          b.date_transaction,
                          a.item_name,
                          a.quantity,
                          a.total_price,
                          CONCAT(c.last_name, ' ', c.first_name) AS fname
                      FROM
                          sales a
                      INNER JOIN sales_payment b ON
                          a.invoice_no = b.invoice_no
                      LEFT JOIN cashier c ON
                          b.cashier_assign = c.cashier_id
                      WHERE date(b.date_transaction)='$date'
                      ORDER BY
                          DATE(b.date_transaction)
                      DESC";
                          $rs = $conn->query($sql);
                          $i = 1;
                          foreach ($rs as $row) {
                          ?>
                            <tr>
                              <td class="sort-name text-capitalize"><?php echo $i++; ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['invoice_no'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['date_transaction'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['item_name'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['quantity'] ?></td>
                              <td class="sort-name"><?php echo $row['discount'] ?></td>
                              <td class="sort-name text-capitalize">₱ <?php echo number_format($row['total_price'], 2) ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['fname'] ?></td>



                            </tr>
                          <?php } ?>

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
                        <form action="" method="post">
                          <div class="input-group mb-2">
                            <select class="form-select" id="floatingSelects" name="monthly" aria-label="Floating label select example" required>
                              <option value="">--</option>
                              <?php
                              $sql = "SELECT year(date_transaction) as years from sales_payment group by year(date_transaction)";
                              $rs = $conn->query($sql);
                              foreach ($rs as $row) { ?>
                                <option value="<?php echo $row['years'] ?>"><?php echo $row['years'] ?></option>
                              <?php } ?>
                            </select>
                            <button class="btn btn-success" name="monthlysearch" type="submit">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                              </svg></button> &nbsp;
                            <button class="btn btn-info" name="printmonthly" type="submit">
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
                                Month
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Year
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Total Earnings
                              </button>
                            </th>
                          </tr>
                        </thead>
                        <tbody class="table-tbody">
                          <?php
                          if (isset($_POST['monthlysearch'])) {
                            $year = $_POST['monthly'];
                          } else {
                            $year = date('Y');
                          }
                          $sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS total,YEAR(a.date_transaction) as year,MONTHNAME(a.date_transaction) as month FROM sales_payment a LEFT JOIN cashonhand b ON date(a.date_transaction) = date(b.date_added) where  YEAR(a.date_transaction)='$year' group by MONTHNAME(a.date_transaction)";
                          $rs = $conn->query($sql);
                          foreach ($rs as $row) {

                          ?>
                            <tr>
                              <td class="sort-name text-capitalize"><?php echo $row['month'] ?></td>
                              <td class="sort-name text-capitalize"><?php echo $row['year'] ?></td>
                              <td class="sort-name text-capitalize">₱ <?php echo number_format($row['total'], 2) ?></td>

                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <br>

                    </div>

                  </div>
                </div>
                <div class="tab-pane fade" id="tabs-activity-8">
                  <div id="listjs">
                    <div class="d-flex align-items-center justify-content-between">
                      <div></div>
                      <form action="" method="post">
                        <div class="flex-shrink-0">
                          <button class="btn btn-primary" name="printyear" type="submit">Print</button>
                        </div>
                      </form>
                    </div>
                    <br>
                    <div id="pagination-container"></div>
                    <div id="table-default" class="table-responsive">
                      <table class="table" id="tables">
                        <thead>
                          <tr>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Year
                              </button>
                            </th>
                            <th>
                              <button class="table-sort" data-sort="sort-name">
                                Total Earnings
                              </button>
                            </th>

                          </tr>
                        </thead>
                        <tbody class="table-tbody">
                          <?php
                          $sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS totals,year(a.date_transaction) as year FROM sales_payment a LEFT JOIN cashonhand b ON YEAR(a.date_transaction) = YEAR(b.date_added) group by YEAR(a.date_transaction)";
                          $rss = $conn->query($sql);
                          foreach ($rss as $rows) {
                          ?>
                            <tr>
                              <td class="sort-name text-capitalize"><?php echo $rows['year'] ?></td>
                              <td class="sort-name text-capitalize">₱ <?php echo number_format($rows['totals'], 2) ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                      <br>

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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Basic Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <input type="hidden" class="form-control" name="id" id="id">
                  <label class="form-label">First Name:</label>
                  <input type="text" class="form-control" name="firstname" id="firstname" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Last Name:</label>
                  <input type="text" class="form-control" name="lastname" id="lastname" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Middle Name:</label>
                  <input type="text" class="form-control" name="middlename" id="middlename" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Phone:</label>
                  <input type="text" class="form-control" name="phone" id="phone" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-3">
                  <label class="form-label">Address:</label>
                  <textarea class="form-control" name="address" rows="3" id="address"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Username:</label>
                  <input type="text" class="form-control" name="username" id="username" readonly required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Password:</label>
                  <input type="password" class="form-control" name="password" id="password" required>
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

</body>

</html>