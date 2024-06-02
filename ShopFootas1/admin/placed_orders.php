<?php

include '../db.php';

if(isset ($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $order_status = $_POST['order_status'];
   $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
   
   $update_payment = $conn->prepare("UPDATE `orders` SET order_status = ? WHERE id = ?");
   $update_payment->bind_param("si", $order_status, $order_id);
   $update_payment->execute();
   $update_payment->close();
   
   $message[] = 'Order status updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->bind_param("i", $delete_id);
   $delete_order->execute();
   $delete_order->close();
   
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../db.php'; ?>

<section class="orders">

<h1 class="heading">Placed Orders</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      $result = $select_orders->get_result();
      if($result->num_rows > 0){
         while($fetch_orders = $result->fetch_assoc()){
   ?>
   <div class="box">
      <p> Placed on : <span><?= $fetch_orders['date_added']; ?></span> </p>
      <p> User ID : <span><?= $fetch_orders['user_info_id']; ?></span> </p>
      <p>Total Price: <span>₱<?= number_format($fetch_orders['total_amt'], 2); ?></span></p>
      <p> Payment: GCash</p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="order_status" class="select">
            <option selected disabled><?= $fetch_orders['order_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_order">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">Delete</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No orders placed yet!</p>';
      }
   ?>

</div>

</section>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>