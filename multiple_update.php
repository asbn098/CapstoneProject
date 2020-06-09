<?php

//multiple_update.php
$connect = new PDO("mysql:host=localhost;dbname=asbn098", "asbn098", "qkrwldus002!");

if(isset($_POST['hidden_number']))
{
 $date = $_POST['date'];
 $node_name = $_POST['node_name'];
 $address = $_POST['address'];
 $product = $_POST['product'];
 $final_box = $_POST['final_box'];
 $deliver = $_POST['deliver'];
 $number = $_POST['hidden_number'];
 for($count = 0; $count < count($number); $count++)
 {
  $data = array(
   ':date'   => $date[$count],
   ':node_name'  => $node_name[$count],
   ':address'  => $address[$count],
   ':product' => $product[$count],
   ':final_box'   => $final_box[$count],
   ':deliver'   => $deliver[$count],
   ':number'   => $number[$count]
  );
  $query = "
  UPDATE Route 
  SET date = :date, node_name = :node_name, address = :address, product = :product, final_box = :final_box , deliver = :deliver  
  WHERE product = :product AND node_name = :node_name AND date = :date AND product = :product
  ";
  $statement = $connect->prepare($query);
  $statement->execute($data);
 }
}

?>