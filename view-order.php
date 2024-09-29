<?php 
    session_start();
    include 'inc/header.php'; 
    include 'function/userfunction.php';
    include 'authenticate.php';

    if(isset($_GET['t'])){
        $tracking_no = $_GET['t'];
        $orderData = checkTrackingNoValid($tracking_no);
        if(mysqli_num_rows($orderData) < 0){
            ?>
                <h4>Something Went Wrong</h4>
            <?php
            die();
        }
    }else{
        ?>
            <h4>Something Went Wrong</h4>
        <?php
        die();
    }
    $data = mysqli_fetch_array($orderData);
?>
<div class="py-3 bg-primary">
    <div class="container">
        <h6 class="text-white">
            <a href="index.php" class="text-white">Home / </a>  
            <a href="my-orders.php" class="text-white">My Orders /</a>  
            <a href="#" class="text-white">View Order</a>
        </h6>
    </div>
</div>
<div class="py-5">
    <div class="container">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <span class="fs-4">View Order</span>
                            <a href="my-orders.php" class="btn btn-warning float-end"> <i class="fa fa-reply"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Delivery Details</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Name</label>
                                            <div class="border p-1">
                                                <?= $data['name'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Email</label>
                                            <div class="border p-1">
                                                <?= $data['email'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Phone</label>
                                            <div class="border p-1">
                                                <?= $data['phone'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Tracking No</label>
                                            <div class="border p-1">
                                                <?= $data['tracking_no'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Address</label>
                                            <div class="border p-1">
                                                <?= $data['address'];?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="fw-bold">Pin Code</label>
                                            <div class="border p-1">
                                                <?= $data['pincode'];?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Order Detail</h4>
                                    <hr>

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $userId = $_SESSION['auth_user']['user_id'];
                                                $order_query = "SELECT o.id AS oid, o.tracking_no, o.user_id, oi.*, oi.qty AS orderqty, p.*
                                                        FROM tbl_order o, tbl_order_item oi, tbl_product p 
                                                        WHERE o.user_id = '$userId' AND oi.order_id = o.id AND p.id = oi.prod_id AND o.tracking_no = '$tracking_no'";
                                                $order_query_run = mysqli_query($con, $order_query);
                                                
                                                if(mysqli_num_rows($order_query_run) > 0){
                                                    foreach ($order_query_run as $item){
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <img src="uploads/<?= $item['image']; ?>" width="50px" height="50px" alt="<?= $item['name']?>"> <?= $item['name']?>
                                                                </td>
                                                                <td><?= $item['price']?></td>
                                                                <td><?= $item['orderqty']?></td>
                                                            </tr>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <h5>Total Price: <span class="float-end fw-bold"><?= $data['total_price'];?></span></h5>
                                    <hr>
                                    <label class="fw-bold">Payment Mode</label>
                                    <div class="border p-1 mb-3">
                                        <?= $data['payment_mode']?>
                                    </div>
                                    <label class="fw-bold">Status</label>
                                    <div class="border p-1 mb-3">
                                        <?php
                                            if($data['status'] == 0){
                                                echo "Under Process";
                                            }else if($data['status'] == 1){
                                                echo "Completed";
                                            }else if($data['status'] == 2){
                                                echo "Cancelled";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

<?php include 'inc/footer.php';?>
    