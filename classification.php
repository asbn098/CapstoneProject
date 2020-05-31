<?php
//index.php
$connect = mysqli_connect("localhost", "asbn098", "qkrwldus002!", "asbn098");
if ( !$connect ) die('DB Error');
$connect ->set_charset("utf8");
$message = '';

if(isset($_POST['upload']))
{
 if($_FILES['product_file']['name'])
 {
  $filename = explode(".", $_FILES['product_file']['name']);
  if(end($filename) == "csv")
  {
   $handle = fopen($_FILES['product_file']['tmp_name'], "r");
   while($data = fgetcsv($handle))
   {
    $node_name = mysqli_real_escape_string($connect, $data[0]);
    $date = mysqli_real_escape_string($connect, $data[1]);  
    $product = mysqli_real_escape_string($connect, $data[2]);
    $address = mysqli_real_escape_string($connect, $data[3]);
    $final_box = mysqli_real_escape_string($connect, $data[4]);
    $save_cost = mysqli_real_escape_string($connect, $data[5]);
    $y_coord = mysqli_real_escape_string($connect, $data[6]);
    $x_coord = mysqli_real_escape_string($connect, $data[7]);

    $query = "
     INSERT INTO PREPROCESS VALUE('$node_name','$date','$product','$address','$final_box','$save_cost','$y_coord','$x_coord');
     
    ";
    mysqli_query($connect, $query);
   }
   fclose($handle);
   header("location: classification.php?updation=1");

    }
  else
  {
   $message = '<label class="text-danger">CSV 파일만 선택해주세요</label>';
  }
 }
 else
 {
  $message = '<label class="text-danger">파일을 선택해주세요</label>';
 }
}

if(isset($_GET["updation"]))
{
 $message = '<label class="text-success">업로드 성공</label>';
}

$query = "SELECT * FROM PREPROCESS";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width",initial-scale="1">
    
    <title>태산팩VRP2</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/태산팩VRP.css">
  </head>
  <body>
    <style type="text/css">
      .card-header {
        /*출처:로젠택배 홈페이지
        margin-left: 0px;
        margin-right: 0px;
        height: 100%;
        width: 98%;
        position: fixed;
        */
        background-size: cover;
        background-color:#F8B159;
        color:white;
        
                
      }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        
        <a class="navbar-brand" href="index2.html">태산팩VRP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="nav navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="http://tspack.co.kr/" target="_blank">태산팩 홈페이지<span class="sr-only"></span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="classification.html">배송분류하기</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="previous.html">이전배송분류</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="route.php">경로확인</a>
            </li>
          </ul>
        </div>
      </div>    
    </nav>

    <div class="container">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <div class="row">
                  <div class="col-md-6">
                  <h4 class="card-title ">주문내역</h4>
                  <p class="card-category"> import한 csv 파일을 전처리한 주문내역</p>
                  </div>
                  <div class="col-md-6">
                    <form method="post" enctype='multipart/form-data'>
                      <label>파일 선택(csv만)</label>
                      <input type="file" name="product_file" />
                     
                      <input type="submit" name="upload" class="btn btn-info" value="Upload" />
                    </form>
                    <?php echo $message; ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                    <th>
                        Num
                      </th>
                      <th>
                        Date
                      </th>
                      <th>
                        Customer
                      </th>
                      <th>
                        Address
                      </th>
                      <th>
                        Items
                      </th>
                      <th>
                        Box
                      </th>
                      <th>
                        Cost(won)
                      </th>
                    </thead>
                    <?php
                    while($row = mysqli_fetch_array($result))
                    {
                      echo '
                      <thead>
                      <td>'.$row["number"].'</td>
                      <td>'.$row["date"].'</td>
                      <td>'.$row["node_name"].'</td>
                      <td>'.$row["address"].'</td>
                      <td>'.$row["product"].'</td>
                      <td>'.$row["final_box"].'</td>
                      <td>'.$row["save_cost"].'</td>
                      </thead>
                      ';
                    }
                    ?>
                    
                  </table>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>


    
    <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>