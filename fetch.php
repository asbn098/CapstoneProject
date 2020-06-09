<?php
//fetch.php
$connect = mysqli_connect("localhost", "asbn098", "qkrwldus002!", "asbn098");
$columns = array('node_name','date', 'product', 'address', 'final_box','save_cost','deliver');

$query = "SELECT * FROM Route WHERE ";

if($_POST["is_date_search"] == "yes")
{
 $query .= 'date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND deliver = "'.$_POST['filter_gender'].'" AND
 ';
}

if(isset($_POST["search"]["value"]))
{
 $query .= '
  (node_name LIKE "%'.$_POST["search"]["value"].'%" 
  OR product LIKE "%'.$_POST["search"]["value"].'%" 
  OR address LIKE "%'.$_POST["search"]["value"].'%" 
  OR final_box LIKE "%'.$_POST["search"]["value"].'%"
  OR save_cost LIKE "%'.$_POST["search"]["value"].'%"
  OR deliver LIKE "%'.$_POST["search"]["value"].'%")
 ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$columns[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' 
 ';
}
else
{
 $query .= 'ORDER BY date DESC ';
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
 $sub_array[] = $row["node_name"];
 $sub_array[] = $row["date"];
 $sub_array[] = $row["product"];
 $sub_array[] = $row["address"];
 $sub_array[] = $row["final_box"];
 $sub_array[] = $row["save_cost"];
 $sub_array[] = $row["deliver"];
 $total_order = $total_order + floatval($row["save_cost"]);
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
 "data"    => $data,
 'total'    => number_format($total_order, 2)
);

echo json_encode($output);

?>