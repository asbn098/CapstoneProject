<?php
//fetch2.php
$connect = mysqli_connect("localhost", "asbn098", "qkrwldus002!", "asbn098");
$columns = array('number','node_name','date', 'address','deliver','x_coord1','y_coord1','x_coord2','y_coord2');

$query = "SELECT * FROM Route WHERE ";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND deliver = "'.$_POST['filter_gender'].'" AND
 ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (number LIKE "%'.$_POST["search"]["value"].'%" 
  OR node_name LIKE "%'.$_POST["search"]["value"].'%" 
  OR date LIKE "%'.$_POST["search"]["value"].'%" 
  OR address LIKE "%'.$_POST["search"]["value"].'%"
  OR deliver LIKE "%'.$_POST["search"]["value"].'%"
  OR x_coord1 LIKE "%'.$_POST["search"]["value"].'%"
  OR y_coord1 LIKE "%'.$_POST["search"]["value"].'%"
  OR x_coord2 LIKE "%'.$_POST["search"]["value"].'%"
  OR y_coord2 LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY number DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
 $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["number"];
 $sub_array[] = $row["node_name"];
 $sub_array[] = $row["address"];
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM Route";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>