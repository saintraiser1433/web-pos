<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}

$datez = date('Y');
$sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS total,MONTHNAME(a.date_transaction) as month FROM sales_payment a LEFT JOIN cashonhand b ON date(a.date_transaction) = date(b.date_added) where YEAR(a.date_transaction)='$datez' group by MONTHNAME(a.date_transaction)";
$rs = $conn->query($sql);
$resultArray = array();

foreach ($rs as $row) {
  $resultArray[] = $row;
}



// Extracting the required data from the array
$cntData = array_column($resultArray, 'total');
$monthData = array_column($resultArray, 'month');


?>
<!DOCTYPE html>
<html lang="en">
<?php
include '../static/nav/head.php'
?>

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
              <!-- Page pre-title -->
              <div class="page-pretitle">
                Overview
              </div>
              <h2 class="page-title">
                Dashboard
              </h2>
            </div>
            <!-- Page title actions -->

          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="row row-deck row-cards">
            <div class="col-12">
              <div class="row row-cards">
                <div class="col-sm-6 col-lg-3">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ruler-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M17 3l4 4l-14 14l-4 -4z"></path>
                              <path d="M16 7l-1.5 -1.5"></path>
                              <path d="M13 10l-1.5 -1.5"></path>
                              <path d="M10 13l-1.5 -1.5"></path>
                              <path d="M7 16l-1.5 -1.5"></path>
                            </svg>
                          </span>
                        </div>
                        <div class="col">
                          <div class="font-weight-bold">
                            <?php
                            $sql = "SELECT COUNT(*) as cnt FROM inventory";
                            $rs = $conn->query($sql);
                            $row = $rs->fetch_assoc();
                            echo $row['cnt'];
                            ?>
                          </div>
                          <div class="text-muted">Assets</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                              <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                              <path d="M17 17h-11v-14h-2" />
                              <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                          </span>
                        </div>
                        <div class="col">
                          <div class="font-weight-bold">
                            ₱
                            <?php
                            $date = date('Y-m-d');
                            $sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS total FROM sales_payment a LEFT JOIN cashonhand b ON date(a.date_transaction) = date(b.date_added) where date(a.date_transaction)='$date' group by date(a.date_transaction)";
                            $rs = $conn->query($sql);
                            $row = $rs->fetch_assoc();
                            if ($rs->num_rows > 0) {
                              echo number_format($row['total'], 2);
                            } else {
                              echo "0.00";;
                            }
                            ?>
                          </div>
                          <div class="text-muted">Daily Sales</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="bg-twitter text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-align-box-left-middle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path>
                              <path d="M9 15h-2"></path>
                              <path d="M13 12h-6"></path>
                              <path d="M11 9h-4"></path>
                            </svg>
                          </span>
                        </div>
                        <div class="col">
                          <div class="font-weight-medium">
                            ₱
                            <?php
                            $month = date('m');
                            $monthname = date('M');
                            $year = date('Y');
                            $sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS total FROM sales_payment a LEFT JOIN cashonhand b ON date(a.date_transaction) = date(b.date_added) where MONTH(a.date_transaction)='$month' and YEAR(a.date_transaction)='$year' group by MONTHNAME(a.date_transaction)";
                            $rs = $conn->query($sql);
                            $row = $rs->fetch_assoc();
                            if ($rs->num_rows > 0) {
                              echo number_format($row['total'], 2);
                            } else {
                              echo "0.00";
                            }
                            ?>

                          </div>
                          <div class="text-muted"><?php echo $monthname ?> -Monthly Sales</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                  <div class="card card-sm">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <span class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M13.018 20.687c-.333 .119 -.673 .223 -1.018 .313a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3c.433 1.472 .575 2.998 .436 4.495"></path>
                              <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                              <path d="M19 21v1m0 -8v1"></path>
                            </svg>
                          </span>
                        </div>
                        <div class="col">
                          <div class="font-weight-medium">
                            ₱
                            <?php
                            $year = date('Y');
                            $sql = "SELECT COALESCE(SUM(a.total) + b.amount, SUM(a.total)) AS total FROM sales_payment a LEFT JOIN cashonhand b ON date(a.date_transaction) = date(b.date_added) where YEAR(a.date_transaction)='$year' group by YEAR(a.date_transaction)";
                            $rs = $conn->query($sql);
                            $row = $rs->fetch_assoc();
                            if ($rs->num_rows > 0) {
                              echo number_format($row['total'], 2);
                            } else {
                              echo "0.00";
                            }
                            ?>
                          </div>
                          <div class="text-muted">Yearly Sales</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">Total Sales Per Month</h3>
                  <div id="chart-mentions" class="chart-lg"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include '../static/nav/footer.php' ?>
    </div>
  </div>














  <!-- Libs JS -->
  <script src="../dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>

  <!-- Tabler Core -->
  <script src="../dist/js/tabler.min.js?1684106062" defer></script>
  <script src="../dist/js/demo.min.js?1684106062" defer></script>

  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function() {
      var cntData = <?php echo json_encode($cntData); ?>;
      var month = <?php echo json_encode($monthData); ?>;
      window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
        chart: {
          type: "bar",
          fontFamily: 'inherit',
          height: 240,
          parentHeightOffset: 0,
          toolbar: {
            show: false,
          },
          animations: {
            enabled: false
          },
          stacked: true,
        },
        plotOptions: {
          bar: {
            columnWidth: '50%',
          }
        },
        dataLabels: {
          enabled: false,
        },
        fill: {
          opacity: 1,
        },
        series: [{
          name: "Sales",
          data: cntData
        }],
        tooltip: {
          theme: 'dark'
        },
        grid: {
          padding: {
            top: -20,
            right: 0,
            left: -4,
            bottom: -4
          },
          strokeDashArray: 4,
          xaxis: {
            lines: {
              show: true
            }
          },
        },
        xaxis: {
          labels: {
            padding: 0,
          },
          tooltip: {
            enabled: false
          },
          axisBorder: {
            show: false,
          },
        },
        yaxis: {
          labels: {
            padding: 4
          },
        },
        labels: month,
        colors: [tabler.getColor("green", 0.8)],
        legend: {
          show: false,
        },
      })).render();
    });
  </script>

</body>

</html>