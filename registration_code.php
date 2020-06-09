<!--
Here, we write code for registration.
-->
<?php
require_once('connection.php');
$name = $gender = $email = $password = $pwd = '';


$name = $_POST['name'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$pwd = $_POST['password'];
$password = MD5($pwd);

$sql = "INSERT INTO tbluser (Name,Gender,Email,Password) VALUES ('$name','$gender','$email','$password')";
$result = mysqli_query($conn, $sql);
if($result)
{
	header("Location: Login.php");
}
else
{
	echo "Error :".$sql;
}
?>