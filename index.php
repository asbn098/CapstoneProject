<!DOCTYPE html>
<html>
<head>
    <title>Filters in PHP</title>
    <link rel="stylesheet" type="text/css" href="태산팩VRP/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript">
      $(function() {
        $("#from").datepicker();
      });
      $(function(){
        $("#to").datepicker();
      });

    </script>


</head>
<body>
  <div class="container">
    <h3 style="text-align: center;font-weight: bold;">PHP FilTERS</h3>
    <div class="row">
      <form class="form-horizontal" action="index.php" method="POST">

        <div class="form-group">
          <label class="col-lg-2 control-label">트럭</label>
          <div class="col-lg-4">
            <input type="radio" name="truck" value="1">트럭 1
            <input type="radio" name="truck" value="2">트럭 2
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label">날짜 선택</label>
          <div class="col-lg-4">
            <input type="text" name="from_date" id="from" class="form-control"value="2019-09-22">
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label">날짜 선택</label>
          <div class="col-lg-4">
            <input type="text" name="to_date" id="to" class="form-control"value="2019-09-22">
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label"></label>
          <div class="col-lg-4">
            <input type="submit" name="submit" class="btn btn-primary" value="확인">
          </div>
        </div>

      </form>
    </div> 
    <div class="row">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <td>거래처</td>
            <td>주소</td>
            <td>배송여부</td>
          </tr>
        </thead>
      </table>
      <tbody>
        <?php
          $conn = mysqli_connect("localhost","asbn098","qkrwldus002!","asbn098");
          if(!$conn){
            die("Connection Failed!.".mysqli_connect_error());
          }
          if(isset($_POST['submit'])){
            $truck = $_POST['truck'];
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];

            if($truck !="" || $from_date !="" || $to_date !=""){
              $query = "SELECT * FROM Route WHERE truck = '$truck' OR from_date = '$from_date' AND to_date = '$to_date'";
              
              $date = mysqli_query($conn, $query) or die('error');
              if(mysqli_num_rows($data)>0){
                while($row = mysqli_fetch_assoc($data)){
                  $node_name = $row['node_name'];
                  $address = $row['address'];
                  $from_date = $row['from_date'];
                  $to_date = $row['to_date'];
                  ?>
                  <tr>
                    <td><?php echo $node_name;?></td>
                    <td><?php echo $address;?></td>
                    <td><button type="submit" class="btn btn-primary pull-right">배송완료 </button></td>
                  </tr>
                  <?php
                }
              }
              else{
                ?>
                <tr>
                  <td>Records Not Found</td>
                </tr>
                <?php
              }
            }

          }
          ?>
      </tbody>

    </div> 
  </div> 

</body>
</html>
