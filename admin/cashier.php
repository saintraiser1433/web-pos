<?php
include '../connection.php';
if (!isset($_SESSION['ad_id'])) {
  header("Location:../index.php");
}


if (isset($_GET['status']) && $_GET['status'] != '') {
  $status = $_GET['status'];
  $id = $_GET['id'];
  $sql = "UPDATE cashier SET status='$status' where cashier_id='$id'";
  if ($conn->query($sql)) {
    $_SESSION['response'] = "Cashier Status succesfully updated";
    $_SESSION['type'] = "success";
  } else {
    $_SESSION['response'] = "Error";
    $_SESSION['type'] = "error";
  }
}



if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $middlename = $_POST['middlename'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];
  $id = $_POST['id'];
  if ($id === '') {
    $sql = "INSERT INTO cashier (username,password,first_name,last_name,middle_name,address,phone,status) VALUES ('$username','$password','$firstname','$lastname','$middlename','$address','$phone','0')";
    if ($conn->query($sql)) {
      $_SESSION['response'] = "Cashier successfully added";
      $_SESSION['type'] = "success";
    } else {
      $_SESSION['response'] = "Error";
      $_SESSION['type'] = "error";
    }
  } else {
    $sqlt = "SELECT * FROM auth where id='$id' and password='$password'";
    $rs = $conn->query($sqlt);
    if ($rs->num_rows > 0) {
      $sql = "UPDATE cashier SET username='$username',password='$password',first_name='$firstname',last_name='$lastname',middle_name='$middlename',address='$address',phone='$phone' WHERE cashier_id='$id'";
      if ($conn->query($sql)) {
        $_SESSION['response'] = "Cashier successfully updated";
        $_SESSION['type'] = "success";
      }
    } else {
      $_SESSION['response'] = "Incorrect password";
      $_SESSION['type'] = "error";
    }
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
              <h2 class="page-title">Cashier List</h2>
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
                        <th style="display:none"></th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            First Name
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Last Name
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Middle Name
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Address
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Phone
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
                            Username
                          </button>
                        </th>
                        <th>
                          <button class="table-sort" data-sort="sort-name">
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
                      $sql = "SELECT * FROM cashier  order by cashier_id";
                      $rs = $conn->query($sql);
                      foreach ($rs as $row) { ?>
                        <tr>
                          <td class="sort-id" style="display:none"><?php echo $row['cashier_id'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['first_name'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['last_name'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['middle_name'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['address'] ?></td>
                          <td class="sort-name text-capitalize"><?php echo $row['phone'] ?></td>
                          <td class="sort-name"><?php echo $row['username'] ?></td>
                          <td class="sort-name">
                            <?php
                            if ($row['status'] === '0') {
                              echo '<a href="?status=1&id=' . $row['cashier_id'] . '" class="badge bg-success">Active</a>';
                            } else {
                              echo '<a href="?status=0&id=' . $row['cashier_id'] . '" class="badge bg-red">Inactive</a>';
                            }

                            ?>


                          </td>
                          <td>


                            <a href="#" class="badge bg-yellow edit">
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





      $(document).on('click', '.edit', function() {
        $('#modal-add').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        $('#id').val(data[0]);
        $('#firstname').val(data[1]);
        $('#lastname').val(data[2]);
        $('#middlename').val(data[3]);
        $('#phone').val(data[4]);
        $('#address').val(data[5]);
        $('#username').val(data[6]);
        $('.modal-title').html('Update Cashier');


      });


      $(document).on('click', '.add', function() {
        $('#modal-add').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function() {
          return $(this).text();
        }).get();
        $('.modal-title').html('Add Cashier');
        $('#firstname').val('');
        $('#lastname').val('');
        $('#middlename').val('');
        $('#phone').val('');
        $('#address').val('');
        $('#username').val('');
        $('#password').val('');
        $('#id').val('');


      });



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
                  table: 'cashier',
                  key: 'cashier_id'
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