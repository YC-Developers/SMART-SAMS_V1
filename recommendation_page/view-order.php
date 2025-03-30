<?php
require '../vendor/autoload.php';

// Function to get all order files
function getOrderFiles()
{
    $logsDir = __DIR__ . '/logs';
    $orderFiles = [];

    if (is_dir($logsDir)) {
        $files = scandir($logsDir);
        foreach ($files as $file) {
            if (strpos($file, 'orders_') === 0 && strpos($file, '.json') !== false) {
                $orderFiles[] = $logsDir . '/' . $file;
            }
        }
    }

    return $orderFiles;
}

// Function to get all orders from files
function getAllOrders()
{
    $orderFiles = getOrderFiles();
    $allOrders = [];

    foreach ($orderFiles as $file) {
        $orders = json_decode(file_get_contents($file), true);
        if (is_array($orders)) {
            $allOrders = array_merge($allOrders, $orders);
        }
    }

    // Sort by timestamp (newest first)
    usort($allOrders, function ($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });

    return $allOrders;
}

// Get all orders for display
$orders = getAllOrders();

// Handle order status update if requested
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $orderId = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_STRING);
    $newStatus = filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING);

    if ($orderId && $newStatus) {
        $orderFiles = getOrderFiles();
        $updated = false;

        foreach ($orderFiles as $file) {
            $ordersInFile = json_decode(file_get_contents($file), true);
            if (is_array($ordersInFile)) {
                foreach ($ordersInFile as &$order) {
                    if ($order['order_id'] === $orderId) {
                        $order['status'] = $newStatus;
                        $updated = true;
                        break;
                    }
                }

                if ($updated) {
                    file_put_contents($file, json_encode($ordersInFile, JSON_PRETTY_PRINT));
                    $_SESSION['status_message'] = "Order status updated successfully!";
                    header("Location: dashboard.php");
                    exit();
                }
            }
        }

        if (!$updated) {
            $_SESSION['status_message'] = "Order not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-SAMS Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .order-card:hover {
            transform: translateY(-5px);
        }

        .status-pending {
            background-color: #fff3cd;
        }

        .status-completed {
            background-color: #d4edda;
        }

        .status-cancelled {
            background-color: #f8d7da;
        }

        .status-in-progress {
            background-color: #cce5ff;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
                <h2 class="text-center mb-4">SMART-SAMS</h2>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="dashboard.php">Orders Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-10 p-4">
                <h1 class="mb-4">Orders Dashboard</h1>

                <?php if (isset($_SESSION['status_message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= $_SESSION['status_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status_message']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Order Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white text-center p-3">
                                            <h4><?= count($orders) ?></h4>
                                            <p class="mb-0">Total Orders</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-dark text-center p-3">
                                            <h4><?= count(array_filter($orders, fn($o) => $o['status'] == 'pending')) ?></h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white text-center p-3">
                                            <h4><?= count(array_filter($orders, fn($o) => $o['status'] == 'in-progress')) ?></h4>
                                            <p class="mb-0">In Progress</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white text-center p-3">
                                            <h4><?= count(array_filter($orders, fn($o) => $o['status'] == 'completed')) ?></h4>
                                            <p class="mb-0">Completed</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($orders as $order): ?>
                        <div class="col-md-6">
                            <div class="card order-card status-<?= str_replace(' ', '-', strtolower($order['status'])) ?>">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Order #<?= htmlspecialchars($order['order_id']) ?></h5>
                                    <span class="badge bg-<?=
                                                            $order['status'] == 'completed' ? 'success' : ($order['status'] == 'in-progress' ? 'info' : ($order['status'] == 'cancelled' ? 'danger' : 'warning'))
                                                            ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p><strong>School:</strong> <?= htmlspecialchars($order['school_name']) ?></p>
                                    <p><strong>Admin:</strong> <?= htmlspecialchars($order['school_admin']) ?></p>
                                    <p><strong>Plan:</strong> <?= htmlspecialchars($order['billing_plan']) ?></p>
                                    <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                                    <p><strong>Contact:</strong> <?= htmlspecialchars($order['email']) ?> / <?= htmlspecialchars($order['phone']) ?></p>
                                    <p><strong>Date:</strong> <?= date('M j, Y H:i', strtotime($order['timestamp'])) ?></p>
                                    <?php if (!empty($order['comments'])): ?>
                                        <div class="mt-3">
                                            <h6>Comments:</h6>
                                            <p class="text-muted"><?= nl2br(htmlspecialchars($order['comments'])) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" class="mt-3">
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                        <div class="input-group">
                                            <select class="form-select" name="new_status">
                                                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="in-progress" <?= $order['status'] == 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                                                <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                                <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>