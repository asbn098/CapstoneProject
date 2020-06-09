<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width",initial-scale="1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>태산팩VRP2</title>
    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/태산팩VRP.css">



    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
  


    <script src="js/bootstrap.js"></script>



  <style type="text/css">
    
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:25px;
   }
   .ml-auto,
.mx-auto {
  margin-left: auto !important;
}
    </style>

  </head>
     
  <body>
    
    
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
  <div class="container box">
    <div class="table-responsive">
    <div class="card">
      <div class="card-header card-header-primary" style="background-size: cover;
        background-color:#F8B159;
        color:white;">
        
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title ">과거배송내역</h4>
              <p class="card-category">과거내역조회</p>
            </div>
            <div class="col-md-6"></div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="col-md-4"></div>
              <div class="col-md-4"></div>
              <div class="col-md-4">
                <div class="form-group">
                   <select name="filter_gender" id="filter_gender" class="form-control" required>
                    <option value="">배송종류선택</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-daterange">
                <div class="col-md-4">
                  <input type="text" name="start_date" id="start_date" class="form-control" />
                </div>
                <div class="col-md-4">
                  <input type="text" name="end_date" id="end_date" class="form-control" />
                </div>  
              </div>
                <div class="col-md-4">
                  <input type="button" name="search" id="search" value="Search" class="btn btn-info" />
                </div>
              
            </div>
          </div>
      </div>
      <div class="card-body">



        <br />
    
        <table id="order_data" class="table table-bordered table-striped">
         <thead>
          <tr>
            <th>거래처</th>
            <th>날짜</th>
            <th>제품</th>
            <th>주소</th>
            <th>박스</th>
            <th>비용</th>
            <th>배송</th>
          </tr>
         </thead>
         <tbody></tbody>
         <tfoot>
          <tr>
           <th colspan="5">Total</th>
           <th id="total_order"></th>
          </tr>
         </tfoot>

        </table>
      </div>
      <br />
      <br />
      <br />
    </div>
    
    </div>
  </div>
      
       

  
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 });

 fetch_data('no');

 function fetch_data(is_date_search, start_date='', end_date='',filter_gender = '')
 {
  var dataTable = $('#order_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   "order" : [],
   "ajax" : {
    url:"fetch.php",
    type:"POST",
    data:{
     is_date_search:is_date_search, start_date:start_date, end_date:end_date,filter_gender:filter_gender
    }
   },
    drawCallback:function(settings)
    {
     $('#total_order').html(settings.json.total);
    }
  });
 }

 $('#search').click(function(){
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var filter_gender = $('#filter_gender').val();

  if(start_date != '' && end_date !='' && filter_gender != '')
  {
   $('#order_data').DataTable().destroy();
   fetch_data('yes', start_date, end_date,filter_gender);
  }
  else
  {
   alert("Both Date is Required");
  }
 }); 
 
});
</script>
  </body>
</html>