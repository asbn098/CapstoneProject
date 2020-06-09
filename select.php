<?php


//database_connection.php

$connect = new PDO("mysql:host=localhost;dbname=asbn098", "asbn098", "qkrwldus002!");


$query = "SELECT * FROM Route ORDER BY id DESC";

$statement = $connect->prepare($query);

if($statement->execute())
{
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }

 echo json_encode($data);
}

?>
