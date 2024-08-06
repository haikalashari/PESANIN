<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['customer_id'])){
   $user_id = $_SESSION['customer_id'];
}else{
   header('location:login.php');
};

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $table_no = $_POST['table_no']; 
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];
 
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);
 
    if($check_cart->rowCount() > 0){
 
          $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, table_no, total_products, total_price) VALUES(?,?,?,?,?)");
          $insert_order->execute([$user_id, $name, $table_no, $total_products, $total_price]);
 
          $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
          $delete_cart->execute([$user_id]);
 
          $message[] = 'Order Berhasil!';
          header('location:orders.php');
       
    }else{
       $message[] = 'Keranjang anda kosong!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style1.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>checkout</h3>
</div>

<section class="checkout">

   <h1 class="title">Orderan Anda</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Pesanan di Keranjang</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">Rp.<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Keranjang anda kosong!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">total :</span><span class="price">Rp.<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">Lihat Keranjang</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $_SESSION['name'] ?>">
   <input type="hidden" name="nomor handphone" value="<?= $_SESSION['phone'] ?>">
   <input type="hidden" name="table_no" value="<?= $_SESSION['table_no'] ?>">

   <div class="user-info">
      <h3>Informasi Anda</h3>
      <p><i class="fas fa-user"></i><span>Nama : <?= $_SESSION['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span>Nomor Handphone : <?= $_SESSION['phone'] ?></span></p>
        <p><i class="fas fa-table"></i><span>Nomor Meja : <?= $_SESSION['table_no'] ?></span></p>
      <input type="submit" value="Order Sekarang" class="btn" style="width:100%; background:var(--red); color:var(--white);" name="submit">
   </div>

</form>
   
</section>









<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script2.js"></script>

</body>
</html>