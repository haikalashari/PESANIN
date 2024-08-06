<?php
// Include the database connection file
include 'components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $nomor_handphone = $_POST['nomor_handphone'];
   $nomor_handphone = filter_var($nomor_handphone, FILTER_SANITIZE_STRING);
   $customer_id = $conn->lastInsertId();
   $table_no = rand(1, 4);
   $table_no = filter_var($table_no, FILTER_SANITIZE_STRING);
   $customer_id = filter_var($customer_id, FILTER_SANITIZE_STRING);

   if($name == '' || $nomor_handphone == ''){
      $message[] = 'masukkan nama dan nomor handphone!';
   } 

    if(!isset($message)){
        $insert_user = $conn->prepare("INSERT INTO `customer`(id, name, phone, table_no) VALUES(?,?,?,?)");
        $insert_user->execute([$customer_id, $name, $nomor_handphone, $table_no]);
        $select_user = $conn->prepare("SELECT * FROM `customer` WHERE name = ? AND phone = ?");
        $select_user->execute([$name, $nomor_handphone]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        $_SESSION['customer_id'] = $customer_id;
        $_SESSION['name'] = $name;
        $_SESSION['phone'] = $nomor_handphone;
        $_SESSION['table_no'] = $table_no;

        if($select_user->rowCount() > 0){
            $_SESSION['customer_id'] = $row['id'];
            header('location:home.php');
        }
    }
    

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style1.css">

</head>
<body>
   

<section class="form-container">

   <form action="" method="post">
      <h3>PESANIN</h3>
      <input type="text" name="name" required placeholder="masukkan nama anda" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="text" name="nomor_handphone" required placeholder="masukkan nomor handphone" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>