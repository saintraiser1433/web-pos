<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
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
              <h2 class="page-title">Cash On Hand List</h2>
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
                        <th>
                          <button class="table-sort" data-sort="sort-id">
                            #
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Cashier Name
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Amount
                          </button>
                        </th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody class="table-tbody">
                      <?php
                      $sql = "SELECT CONCAT(b.last_name,',',b.first_name,' ',LEFT(b.middle_name, 1)) as fname,a.amount,a.date_added FROM cashonhand a,cashier b where a.cashier_id=b.cashier_id  order by a.date_added";
                      $rs = $conn->query($sql);
                      $i = 1;
                      foreach ($rs as $row) { ?>
                        <tr>
                          <td class="sort-id"><?php echo $i++; ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['fname'] ?></td>
                          <td class="sort-name text-capitalize">₱ <?php echo number_format($row['amount'], 2) ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['date_added'] ?></td>
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
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Cash on Hand</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Cashier:</label>
            <select name="cashier" id="cashiers" class="form-select form-control w-100 text-capitalize">
              <?php
              $sql = "SELECT * FROM cashier where status=0 order by cashier_id";
              $rs = $conn->query($sql);
              foreach ($rs as $row) {
              ?>
                <option value="<?php echo $row['cashier_id'] ?>"><?php echo $row['last_name'] . "," . $row['first_name'] . " " . $row['middle_name'][0] ?></option>

              <?php } ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Cash:</label>
            <input type="number" class="form-control" name="cash" id="cashs" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Date :</label>
            <input type="date" class="form-control" name="date" id="dates" required>
          </div>
        </div>

        <div class="modal-footer">
          <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Cancel
          </a>
          <button type="submit" class="btn btn-primary ms-auto" name="submit" id="cashsubmit">
            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            Save
          </button>
        </div>

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
      $(document).on('click', '.add', function() {
        $('#modal-add').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        $('.modal-title').html('Add Cash On Hand');
      });

      $(document).on('click', '#cashsubmit', function(e) {
        e.preventDefault();
        var cashier = $('#cashiers').val();
        var cash = $('#cashs').val();
        var date = $('#dates').val();
        swal({
            title: "Are you sure?",
            text: "Once submit, it can't be reversable",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                method: "POST",
                url: "../static/ajax/cashonhand.php",
                data: {
                  cashier: cashier,
                  cash: cash,
                  date: date
                },
                success: function(html) {
                  if (html === '1') {
                    swal({
                      title: "Already inserted cash on hand in this date and cashier.",
                      icon: "error",
                      button: "Exit!",
                    })
                  } else {
                    swal("Successfully added", {
                      icon: "success",
                    }).then((value) => {
                      location.reload();
                    });
                  }

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