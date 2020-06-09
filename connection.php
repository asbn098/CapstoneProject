<!--
in this file we write code for connection with database.
-->
<?php
$conn = mysqli_connect("localhost","asbn098","qkrwldus002!","asbn098");

if(!$conn)
{
	echo "Database connection faild...";
}
?>