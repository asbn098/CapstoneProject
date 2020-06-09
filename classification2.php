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
    $y_coord = mysqli_real_escape_string($connect, $data[7]);
    $x_coord = mysqli_real_escape_string($connect, $data[8]);

    $query = "
     INSERT INTO Route VALUE('$number','$node_name','$date','$product','$address','$final_box','$save_cost','$x_coord','$y_coord');
     
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

$query = "SELECT * FROM Route";
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
                <form method="post" id="update_form">
                    <div>
                        <input type="submit" name="multiple_update" id="multiple_update" class="btn btn-info" value="Multiple Update" />
                    </div>
                    <br />
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th width="5%"></th>
                                <th width="10%">날짜</th>
                                <th width="15%">거래처</th>
                                <th width="20%">주소</th>
                                <th width="20%">품목</th>
                                <th width="10%">박스</th>
                                <th width="10%">배송</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </form>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>


    
    <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>  
$(document).ready(function(){  
    
    function fetch_data()
    {
        $.ajax({
            url:"select.php",
            method:"POST",
            dataType:"json",
            success:function(data)
            {
                var html = '';
                for(var count = 0; count < data.length; count++)
                {
                    html += '<tr>';
                    html += '<td><input type="checkbox" number="'+data[count].number+'" data-date="'+data[count].date+'" data-node_name="'+data[count].node_name+'" data-address="'+data[count].address+'" data-product="'+data[count].product+'" data-final_box="'+data[count].final_box+'" data-deliver="'+data[count].deliver+'" class="check_box"  /></td>';
                    
                    html += '<td>'+data[count].date+'</td>';
                    html += '<td>'+data[count].node_name+'</td>';
                    html += '<td>'+data[count].address+'</td>';
                    html += '<td>'+data[count].product+'</td>';
                    html += '<td>'+data[count].final_box+'</td>';
                    html += '<td>'+data[count].deliver+'</td></tr>';
                }
                $('tbody').html(html);
            }
        });
    }

    fetch_data();

    $(document).on('click', '.check_box', function(){
        var html = '';
        if(this.checked)
        {
            html = '<td><input type="checkbox" number="'+$(this).attr('number')+'" data-date="'+$(this).data('date')+'" data-node_name="'+$(this).data('node_name')+'" data-address="'+$(this).data('address')+'" data-product="'+$(this).data('product')+'" data-final_box="'+$(this).data('final_box')+'" data-deliver="'+$(this).data('deliver')+'" class="check_box" checked /></td>';
            html += '<td><input type="text" name="date[]" class="form-control" value="'+$(this).data("date")+'" /></td>';
            html += '<td><input type="text" name="node_name[]" class="form-control" value="'+$(this).data("node_name")+'" /></td>';
            html += '<td><input type="text" name="address[]" class="form-control" value="'+$(this).data("address")+'" /></td>';
            html += '<td><input type="text" name="product[]" class="form-control" value="'+$(this).data("product")+'" /></td>';
            html += '<td><input type="text" name="final_box[]" class="form-control" value="'+$(this).data("final_box")+'" /><input type="hidden" name="hidden_number[]" value="'+$(this).attr('number')+'" /></td>';
            html += '<td><select name="deliver[]" id="deliver_'+$(this).attr('number')+'" class="form-control"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select></td>';
            
        }
        else
        {
          html = '<td><input type="checkbox" number="'+$(this).attr('number')+'" data-date="'+$(this).data('date')+'" data-node_name="'+$(this).data('node_name')+'" data-address="'+$(this).data('address')+'" data-product="'+$(this).data('product')+'" data-final_box="'+$(this).data('final_box')+'" data-deliver="'+$(this).data('deliver')+'" class="check_box" checked /></td>';
            html += '<td>'+$(this).data('date')+'</td>';
            html += '<td>'+$(this).data('node_name')+'</td>';
            html += '<td>'+$(this).data('address')+'</td>';
            html += '<td>'+$(this).data('product')+'</td>';
            html += '<td>'+$(this).data('final_box')+'</td>'; 
            html += '<td>'+$(this).data('deliver')+'</td>';           
        }
        $(this).closest('tr').html(html);
        $('#deliver_'+$(this).attr('number')+'').val($(this).data('deliver'));
    });

    $('#update_form').on('submit', function(event){
        event.preventDefault();
        if($('.check_box:checked').length > 0)
        {
            $.ajax({
                url:"multiple_update.php",
                method:"POST",
                data:$(this).serialize(),
                success:function()
                {
                    alert('수정을 완료했습니다.');
                    fetch_data();
                }
            })
        }
    });

});  
</script>
  </body>
</html>