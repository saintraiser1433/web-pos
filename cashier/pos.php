<?php
include '../connection.php';

if (!isset($_SESSION['cashier_id'])) {
  header("Location:index.php");
}

$cashierid = $_SESSION['cashier_id'];
if (!isset($_SESSION['discounts'])) {
  $_SESSION['discounts'] = 0.00;
}

if (isset($_POST['savediscount'])) {
  $dis = $_POST['discount'];
  $discount = $dis / 100;
  $_SESSION['discounts'] = $discount;
}

$sqlz = "SELECT COUNT(*) as cnt FROM cart where cashier_id='$cashierid'";
$rz = $conn->query($sqlz);
$roz = $rz->fetch_assoc();
$cnt = $roz['cnt'];




if (isset($_POST['submit'])) {

  $inv = $_POST['ids'];
  $quantity = $_POST['quantity'];
  $sql = "SELECT * FROM inventory where inventory_id='$inv'";
  $rs = $conn->query($sql);
  $row = $rs->fetch_assoc();
  if ($row['quantity'] < $quantity) {
    $_SESSION['response'] = "Insufficient Quantity";
    $_SESSION['type'] = "error";
  } else {
    $sq = "SELECT * FROM cart where inventory_id='$inv' and cashier_id='$cashierid'";
    $rsq = $conn->query($sq);
    if ($rsq->num_rows > 0) {
      $upsql = "UPDATE cart SET quantity = quantity + $quantity where inventory_id='$inv' and cashier_id='$cashierid'";
      $conn->query($upsql);
    } else {
      $sqlt = "INSERT INTO cart (inventory_id,quantity,cashier_id) values ('$inv','$quantity','$cashierid')";
      $conn->query($sqlt);
    }
    header("Location:pos.php");
  }
}

if (isset($_GET['crt'])) {
  $id = $_GET['crt'];
  $sql = "DELETE FROM cart where cart_id='$id' and cashier_id='$cashierid'";
  $conn->query($sql);
  header("Location:pos.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include '../static/nav/head.php' ?>

<body class="layout-fluid">
  <script src="../dist/js/demo-theme.min.js?1684106062"></script>
  <div class="page">
    <!-- Navbar -->
    <?php include '../static/nav/topbar.php' ?>
    <div class="page-wrapper">

      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          <div class="row">
            <div class="col-lg-7">
              <div class="card">
                <div class="card-header">

                  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                      <a href="#all" class="nav-link active" data-bs-toggle="tab">All</a>
                    </li>
                    <?php
                    $sql = "SELECT * FROM category order by category_id";
                    $rs = $conn->query($sql);
                    foreach ($rs as $row) { ?>
                      <li class="nav-item">
                        <a href="#<?php echo $row['description'] ?>" class="nav-link" data-bs-toggle="tab"><?php echo $row['description'] ?></a>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="all">
                      <div class="row mb-3">
                        <div class="col-lg-12">
                          <div class="input-icon">
                            <span class="input-icon-addon">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                <path d="M5 11h1v2h-1z" />
                                <path d="M10 11l0 2" />
                                <path d="M14 11h1v2h-1z" />
                                <path d="M19 11l0 2" />
                              </svg>
                            </span>
                            <video style="display:none" id="preview" width="100%" height="150%"></video>
                            <input type="text" id="searchInput" class="form-control" placeholder="Scan QR CODE...." autocomplete="off">
                          </div>
                        </div>
                      </div>
                      <div class="row" id="cardContainer">
                        <?php
                        $sqls = "SELECT * FROM inventory where status=0";
                        $rss = $conn->query($sqls);
                        foreach ($rss as $rows) { ?>
                          <div class="col-lg-3 pb-2">

                            <div class="card card-link card-link-pop" onclick="quantity('<?php echo $rows['inventory_id'] ?>','<?php echo $rows['quantity'] ?>')" style="cursor:pointer">

                              <?php
                              if ($rows['quantity'] === '0') {
                                echo "<div class='ribbon bg-danger'> Out of Stock </div>";
                              } else {
                                echo "<div class='ribbon bg-success'> Qty : " . $rows['quantity'] . "</div>";
                              }
                              ?>
                              <div class="card-status-bottom bg-success"></div>
                              <!-- Photo -->
                              <div class="img-responsive img-responsive-4x3 card-img-top" style="background-image: url('<?php echo $rows['photo'] ?>')"></div>
                              <div class="card-body">
                                <span class="text-capitalize fw-bolder asname"><?php echo $rows['item_name'] ?></span>
                                <p class="text-muted asdes"><?php echo $rows['description'] ?></p>

                                <hr>
                                <h4>₱ <?php echo $rows['price'] ?></h4>
                              </div>
                            </div>
                          </div>

                        <?php } ?>
                      </div>
                    </div>
                    <?php
                    foreach ($rs as $row) { ?>
                      <!-- <div class="tab-pane fade active show" id="tabs-home-8"> -->
                      <div class="tab-pane fade show" id="<?php echo $row['description'] ?>">
                        <div class="row">
                          <?php
                          $catid = $row['category_id'];
                          $sql = "SELECT * FROM inventory where category_id='$catid' and status=0";
                          $rs = $conn->query($sql);
                          foreach ($rs as $row) { ?>
                            <div class="col-lg-3 pb-2">

                              <div class="card card-link card-link-pop" onclick="quantity('<?php echo $row['inventory_id'] ?>','<?php echo $row['quantity'] ?>')" style="cursor:pointer">
                                <?php
                                if ($row['quantity'] === '0') {
                                  echo "<div class='ribbon bg-danger'> Out of Stock </div>";
                                } else {
                                  echo "<div class='ribbon bg-success'> Qty : " . $row['quantity'] . "</div>";
                                }
                                ?>
                                <div class="card-status-bottom bg-success"></div>
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-4x3 card-img-top" style="background-image: url('<?php echo $row['photo'] ?>')"></div>
                                <div class="card-body">
                                  <span class="text-capitalize fw-bolder"><?php echo $row['item_name'] ?></span>
                                  <p class="text-muted"><?php echo $row['description'] ?></p>

                                  <hr>
                                  <h4>₱ <?php echo $row['price'] ?></h4>
                                </div>
                              </div>
                            </div>

                          <?php } ?>
                        </div>
                      </div>
                    <?php
                    } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="card">
                <div class="card-header">
                  <h1 class="card-title text-muted">CURRENT ORDERS</h1>
                  <div class="card-actions">
                    <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal-discount">
                      <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 15l3 -3m2 -2l1 -1" />
                        <path d="M9.148 9.145a.498 .498 0 0 0 .352 .855a.5 .5 0 0 0 .35 -.142" />
                        <path d="M14.148 14.145a.498 .498 0 0 0 .352 .855a.5 .5 0 0 0 .35 -.142" />
                        <path d="M8.887 4.89a2.2 2.2 0 0 0 .863 -.53l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.528 .858m-.757 3.248a2.193 2.193 0 0 1 -1.555 .644h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1c0 -.604 .244 -1.152 .638 -1.55" />
                        <path d="M3 3l18 18" />
                      </svg>
                      (F1) DISCOUNT
                    </a>
                    <a href="#" class="btn btn-outline-primary clearall">
                      <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 6h12" />
                        <path d="M6 12h12" />
                        <path d="M4 18h12" />
                      </svg>
                      (F2) Clear All
                    </a>
                  </div>
                </div>
                <form action="" method="post">
                  <div class="card-body p-0 p-lg-0">
                    <div id="listjs">
                      <div id="table-default" class="table-responsive">
                        <table class="table" id="tables">
                          <thead>
                            <tr>

                              <th>Img</th>
                              <th>
                                <button class="table-sort" data-sort="sort-name">
                                  Item Name
                                </button>
                              </th>
                              <th class="qtys">
                                <button class="table-sort qty" data-sort="sort-gender">
                                  Quantity
                                </button>
                              </th>
                              <th>
                                <button class="table-sort price" data-sort="sort-gender">
                                  Price
                                </button>
                              </th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody class="table-tbody">
                            <?php
                            $sql = "SELECT a.quantity,a.cart_id,b.item_name,b.price,b.photo from cart a , inventory b where a.inventory_id=b.inventory_id and cashier_id='$cashierid' order by a.cart_id ";
                            $rsq = $conn->query($sql);
                            $sum = 0; // Initialize the sum variable
                            if ($rsq->num_rows > 0) {
                              $checkorder =  1;
                              foreach ($rsq as $rows) {
                                $totalPrice = $rows['quantity'] * $rows['price'];
                                $sum += $totalPrice;
                            ?>
                                <tr>
                                  <td>
                                    <div class="avatar bg-muted-lt" data-demo-color>
                                      <img src="<?php echo $rows['photo']; ?>" class="rounded-circle" style="width:30px;height:30px;">
                                    </div>
                                  </td>
                                  <td class="sort-name text-capitalize w-25"><span class="text-gray"><?php echo $rows['item_name'] ?></span></td>

                                  <td>
                                    <?php echo $rows['quantity'] ?>
                                  </td>
                                  <td class="pricetd"> ₱<?php echo number_format($totalPrice, 2)  ?> </td>
                                  <td><a href="?crt=<?php echo $rows['cart_id'] ?>" class="badge bg-danger">
                                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-playstation-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z"></path>
                                        <path d="M8.5 8.5l7 7"></path>
                                        <path d="M8.5 15.5l7 -7"></path>
                                      </svg></a></td>

                                </tr>
                            <?php }
                            } else {
                              $checkorder =  0;
                              echo '
                             <tr>
                            <td colspan="5" class="text-center">No orders found</td>
                             </tr>';
                            }
                            ?>

                          </tbody>
                        </table>

                      </div>

                    </div>
                    <br><br><br><br><br><br><br>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="card text-white" style="background-color: #50C878;">
                          <div class="card-body p-3">
                            <div class="row">
                              <div class="col-lg-6 col-md-6 col-6">Subtotal</div>
                              <div class="col-lg-6 col-md-6 col-6 text-end sub-total">₱ <?php echo number_format($sum, 2)  ?></div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6 col-md-6  col-6">Discount</div>
                              <div class="col-lg-6  col-md-6 col-6 text-end">
                                ₱ -<?php
                                    $discountedAmount = $_SESSION['discounts'] * $sum;
                                    echo number_format($discountedAmount, 2);
                                    ?></div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-lg-6 col-md-6  col-6">
                                <h1>Grand Total</h1>
                              </div>
                              <div class="col-lg-6  col-md-6 col-6 text-end">
                                <h1>₱ <?php

                                      $grandtotal = $sum - $discountedAmount;
                                      echo number_format($grandtotal, 2);
                                      ?></h1>
                                <input type="hidden" id="grandtotal" name="grandtotal" value="<?php echo $grandtotal ?>">
                                <input type="hidden" name="subtotal" id="subtotal" value="<?php echo $sum ?>">
                                <input type="hidden" name="change" id="changes">

                              </div>

                            </div>
                            <div class="row">
                              <div class="col-lg-6 col-md-6  col-6">
                                <h1>Change</h1>
                              </div>
                              <div class="col-lg-6  col-md-6 col-6 text-end">
                                <h1 class="change">₱ 0.00</h1>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-3 p-2">
                      <div class="col-lg-12">
                        <h1 class="card-title">Cash Tendered</h1>
                        <input type="number" class="form-control" name="cashtendered" id="cashtendered" required>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-lg-12">
                        <?php
                        if ($checkorder === 0) {
                          $disabled = 'disabled';
                        } else {
                          $disabled = '';
                        }

                        ?>
                        <button type="button" id="savebill" class="btn btn-primary w-100" <?php echo $disabled ?>>
                          Print Bills
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <?php include '../static/nav/footer.php'; ?>
  </div>
  </div>



  <div class="modal modal-blur fade" id="modal-quantity" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <form action="" method="post">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-success"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-corner-up-right-double  mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M4 18v-6a3 3 0 0 1 3 -3h7"></path>
              <path d="M10 13l4 -4l-4 -4m5 8l4 -4l-4 -4"></path>
            </svg>
            <h3>Enter Quantity</h3>
            <div class="row">
              <input type="hidden" name="ids" id="ids" class="form-control">
              <div class="col-lg-12">

                <input type="number" name="quantity" class="form-control" min="1" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                  <button type="submit" name="submit" class="btn btn-success w-100">
                    Add
                  </button>
                </div>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div>
  </div>

  <div class="modal modal-blur fade" id="modal-discount" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <form action="" method="post">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-success"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-corner-up-right-double  mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M4 18v-6a3 3 0 0 1 3 -3h7"></path>
              <path d="M10 13l4 -4l-4 -4m5 8l4 -4l-4 -4"></path>
            </svg>
            <h3>Discount Type</h3>
            <div class="row">

              <div class="col-lg-12">
                <select name="discount" class="form-select form-control w-100">
                  <?php
                  $sql = "SELECT * FROM discount where status=0 order by discount_id";
                  $rs = $conn->query($sql);
                  foreach ($rs as $row) {
                  ?>
                    <option value="<?php echo $row['percent'] ?>"><?php echo $row['description'] ?></option>

                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                  <button type="submit" name="savediscount" class="btn btn-success w-100">
                    Add Discount
                  </button>
                </div>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div>
  </div>

  <?php include '../static/nav/scripts.php' ?>
  <script src="../dist/js/instascan.min.js"></script>



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
<script>
  let scanner = new Instascan.Scanner({
    video: document.getElementById('preview')
  });

  Instascan.Camera.getCameras().then(function(cameras) {
    if (cameras.length > 0) {
      scanner.start(cameras[0]);
    } else {
      console.error('No cameras found.');
    }
  }).catch(function(e) {
    console.error(e);
  });
  scanner.addListener('scan', function(c) {
    $.ajax({
      method: "POST",
      url: "../static/ajax/inventory.php",
      data: {
        id: c,

      },
      success: function(html) {
        if (html == "no") {
          swal({
            title: "This item is not listed in inventory or unavailable",
            icon: "error",
            button: "Exit!",
          })
        } else if (html > 0) {
          $('#modal-quantity').modal('show');
          $('#ids').val(c);
        } else {
          swal({
            title: "This item is out of stock",
            icon: "error",
            button: "Exit!",
          })
        }
        $("#searchInput").val(c);
      }

    });



  });
</script>
<script>
  var a = <?php echo $cnt ?>;
  if (a === 0) {
    $('#cashtendered').attr('disabled', true);
  } else {
    $('#cashtendered').attr('disabled', false);
  }
</script>

<script>
  $(document).ready(function() {
    $("#searchInput").autocomplete({
      source: "../static/ajax/fetchitems.php",
      minLength: 1,
      select: function(event, ui) {
        if (ui.item.qty > 0) {
          $('#modal-quantity').modal('show');
          $('#ids').val(ui.item.id);
        } else {
          swal({
            title: "This item is out of stock",
            icon: "error",
            button: "Exit!",
          })
        }

      }
    }).data("ui-autocomplete")._renderItem = function(ul, item) {
      return $("<li class='ui-autocomplete-row'></li>")
        .data("item.autocomplete", item)
        .append(item.label)
        .appendTo(ul);
    };
  });
</script>
<script>
  function quantity(itemid, quantity) {
    if (quantity > 0) {
      $('#modal-quantity').modal('show');
      $('#ids').val(itemid);
    } else {
      swal({
        title: "This item is out of stock",
        icon: "error",
        button: "Exit!",
      })
    }

  }

  $(document).ready(function() {
    $('#cashtendered').on('keyup', function() {
      var num1 = parseFloat($('#cashtendered').val()) || 0; // Parse the input as a floating-point number
      var num2 = parseFloat($('#grandtotal').val()) || 0;
      var sum = num1 - num2;
      var roundedSum = sum.toFixed(2);
      var formattedSum = parseFloat(roundedSum).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }); // Round the sum to 2 decimal places
      if (roundedSum < 0) {
        $('#savebill').attr('disabled', true);
      } else {
        $('#savebill').attr('disabled', false);
      }
      $('.change').html('₱ ' + formattedSum);
      $('#changes').val(roundedSum);

    });
  });


  $(document).keydown(function(event) {
    if (event.key === 'F1') {
      event.preventDefault();
      $('#modal-discount').modal('show');
    }
    if (event.key === 'F2') {
      event.preventDefault();
      swal({
          title: "Are you sure?",
          text: "You want to clear all orders ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              method: "POST",
              url: "../static/ajax/deleteall.php",
              success: function(html) {
                location.reload();
              }
            });
          }
        });
    }

  });

  $(document).on('click', '#savebill', function(e) {
    var cash = $('#cashtendered').val();
    var grandtotal = $('#grandtotal').val();
    var subtotal = $('#subtotal').val();
    var discount = $('#discount').val();
    var change = $('#changes').val();
    e.preventDefault();
    if ($.isEmptyObject(cash)) {

      swal({
        title: "Please enter cash before proceed to bill",
        icon: "error",
        button: "Exit!",
      })
    } else {
      swal({
          title: "Are you sure?",
          text: "Click 'Bill Button' if the transaction is complete.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              method: "POST",
              url: "../static/ajax/savebill.php",
              data: {
                cashtendered: cash,
                grandtotal: grandtotal,
                subtotal: subtotal,
                discount: discount,
                change: change
              },
              success: function(html) {
                swal("Success! ", {
                  icon: "success",
                }).then((value) => {
                  setInterval(function() {
                    window.location.href = '../static/report/output/receipt.pdf';
                  }, 1000);


                });
              }

            });

          }
        });
    }

  });


  $(document).on('click', '.clearall', function(e) {
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "You want to clear all orders ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "POST",
            url: "../static/ajax/deleteall.php",
            success: function(html) {
              location.reload();

            }
          });
        }
      });
  });
</script>

<style type="text/css">
  .ui-autocomplete-row {
    padding: 8px;
    background-color: #f4f4f4;
    border-bottom: 1px solid #ccc;
    font-weight: bold;
  }

  .ui-autocomplete-row:hover {
    background-color: #ddd;
  }
</style>