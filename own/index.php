<?php
include '../Includes/dbcon.php';
include '../Includes/session3.php';

// Function to get all orders from log files
function getAllOrders() {
    $logFile = __DIR__ . '/../recommendation_page/logs/orders_2025-03.json';
    $allOrders = [];

    if (file_exists($logFile)) {
        $orders = json_decode(file_get_contents($logFile), true);
        if (is_array($orders)) {
            $allOrders = $orders;
        }
    }

    // Sort by timestamp (newest first)
    usort($allOrders, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });

    return $allOrders;
}

$orders = getAllOrders();

// Handle status update if requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_STRING);
    $newStatus = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);
    
    if ($orderId && $newStatus) {
        $logFile = __DIR__ . '/../recommendation_page/logs/orders_2025-03.json';
        
        if (file_exists($logFile)) {
            $ordersInFile = json_decode(file_get_contents($logFile), true);
            $updated = false;
            
            if (is_array($ordersInFile)) {
                foreach ($ordersInFile as &$order) {
                    if ($order['order_id'] === $orderId) {
                        $order['status'] = $newStatus;
                        $updated = true;
                        break;
                    }
                }
                
                if ($updated) {
                    file_put_contents($logFile, json_encode($ordersInFile, JSON_PRETTY_PRINT));
                    $_SESSION['status_message'] = "Order status updated successfully!";
                    header("Location: index.php");
                    exit();
                }
            }
        }
        
        if (!isset($_SESSION['status_message'])) {
            $_SESSION['status_message'] = "Order not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <style>
    .order-card {
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    .order-card:hover {
      transform: translateY(-5px);
    }
    .status-pending {
      border-left: 4px solid #ffc107;
    }
    .status-completed {
      border-left: 4px solid #28a745;
    }
    .status-cancelled {
      border-left: 4px solid #dc3545;
    }
    .status-in-progress {
      border-left: 4px solid #17a2b8;
    }
    .badge-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-completed {
      background-color: #28a745;
    }
    .badge-cancelled {
      background-color: #dc3545;
    }
    .badge-in-progress {
      background-color: #17a2b8;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php";?>
        <!-- Topbar -->
        
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">AMS Owner Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <!-- Status Message -->
          <?php if (isset($_SESSION['status_message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
              <?= $_SESSION['status_message'] ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php unset($_SESSION['status_message']); ?>
          <?php endif; ?>

          <div class="row mb-3">
            <!-- Students Card -->
            <?php 
            $query1 = mysqli_query($conn,"SELECT * from tblstudents");                       
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">total Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-graduate fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Class Card -->
            <?php 
            $query1 = mysqli_query($conn,"SELECT * from schools");                       
            $schl = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">total Schools</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $schl;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-school fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Teachers Card -->
            <?php 
            $query1 = mysqli_query($conn,"SELECT * from tblteachers");                       
            $sessTerm = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">total teachers</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $sessTerm;?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Orders Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Orders</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php 
                        $pendingOrders = array_filter($orders, function($o) { 
                            return $o['status'] == 'pending'; 
                        });
                        echo count($pendingOrders);
                        ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-shopping-cart fa-2x text-danger"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->

          <!-- Orders Section -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Order ID</th>
                        <th>School</th>
                        <th>Admin</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($orders as $order): ?>
                        <tr>
                          <td><?= htmlspecialchars($order['order_id']) ?></td>
                          <td><?= htmlspecialchars($order['school_name']) ?></td>
                          <td><?= htmlspecialchars($order['school_admin']) ?></td>
                          <td><?= htmlspecialchars($order['billing_plan']) ?></td>
                          <td>
                            <span class="badge badge-<?= 
                              $order['status'] == 'completed' ? 'completed' : 
                              ($order['status'] == 'in-progress' ? 'in-progress' : 
                              ($order['status'] == 'cancelled' ? 'cancelled' : 'pending'))
                            ?>">
                              <?= ucfirst($order['status']) ?>
                            </span>
                          </td>
                          <td><?= date('M j, Y H:i', strtotime($order['timestamp'])) ?></td>
                          <td>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#orderModal<?= str_replace('-', '', $order['order_id']) ?>">
                              View
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Modals -->
          <?php foreach ($orders as $order): ?>
            <div class="modal fade" id="orderModal<?= str_replace('-', '', $order['order_id']) ?>" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details - <?= htmlspecialchars($order['order_id']) ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <p><strong>School Name:</strong> <?= htmlspecialchars($order['school_name']) ?></p>
                        <p><strong>Admin Name:</strong> <?= htmlspecialchars($order['school_admin']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                      </div>
                      <div class="col-md-6">
                        <p><strong>Billing Plan:</strong> <?= htmlspecialchars($order['billing_plan']) ?></p>
                        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                        <p><strong>Order Date:</strong> <?= date('M j, Y H:i', strtotime($order['timestamp'])) ?></p>
                        <p><strong>Status:</strong> 
                          <span class="badge badge-<?= 
                            $order['status'] == 'completed' ? 'completed' : 
                            ($order['status'] == 'in-progress' ? 'in-progress' : 
                            ($order['status'] == 'cancelled' ? 'cancelled' : 'pending'))
                          ?>">
                            <?= ucfirst($order['status']) ?>
                          </span>
                        </p>
                      </div>
                    </div>
                    <?php if (!empty($order['comments'])): ?>
                      <div class="row mt-3">
                        <div class="col-md-12">
                          <h6>Additional Comments:</h6>
                          <p><?= nl2br(htmlspecialchars($order['comments'])) ?></p>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <hr>
                    <form method="post">
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Update Status</label>
                        <div class="col-sm-7">
                          <select class="form-control" name="new_status">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in-progress" <?= $order['status'] == 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                          </select>
                        </div>
                        <div class="col-sm-2">
                          <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable({
        "order": [[5, "desc"]], // Default sort by date (column 5) descending
        "pageLength": 10
      });
    });
  </script>
</body>
</html>