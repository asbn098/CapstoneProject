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
    $number = mysqli_real_escape_string($connect, $data[0]);
    $node_name = mysqli_real_escape_string($connect, $data[1]);
    $date = mysqli_real_escape_string($connect, $data[2]);  
    $product = mysqli_real_escape_string($connect, $data[3]);
    $address = mysqli_real_escape_string($connect, $data[4]);
    $final_box = mysqli_real_escape_string($connect, $data[5]);
    $save_cost = mysqli_real_escape_string($connect, $data[6]);
    $x_coord1 = mysqli_real_escape_string($connect, $data[7]);
    $y_coord1 = mysqli_real_escape_string($connect, $data[8]);
    $deliver = mysqli_real_escape_string($connect, $data[9]);
    $x_coord2 = mysqli_real_escape_string($connect, $data[10]);
    $y_coord2 = mysqli_real_escape_string($connect, $data[11]);

    $query = "
    INSERT INTO Route (number,node_name,date,product,address,final_box,save_cost,x_coord1,y_coord1,deliver,x_coord2,y_coord2)
     VALUES ('$number','$node_name','$date','$product','$address','$final_box','$save_cost','$x_coord1','$y_coord1','$deliver','$x_coord2','$y_coord2')";

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

$query = "SELECT * FROM Route ORDER BY id DESC";
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
              <a class="nav-link" href="classification.php">주문내역보기</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="previous.php">이전배송분류</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="routeexmple.php">경로확인</a>
            </li>
          </ul>
          <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">접속하기<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="index.html">로그아웃</a></li>
              </ul>
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
                      <input type="file" name="product_file"/>
                     
                      <input type="submit" name="upload" class="btn btn-info" value="업로드" />
                      <button type="button" class="btn btn-primary"onclick="location.href='classification2.php' ">분류하기</button>
                    </form>
                    
                    <?php echo $message; ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <th>날짜</th>
                      <th>거래처</th>
                      <th>주소</th>
                      <th>품목</th>
                      <th>박스</th>
                      <th>외주비용</th>
                    </thead>
                    <tbody></tbody>
                    <?php
                    while($row = mysqli_fetch_array($result))
                    {
                      echo '
                      <thead>
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