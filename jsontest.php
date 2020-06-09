<?php 
// DB 접속 
$con=mysqli_connect("localhost","asbn098","qkrwldus002!","asbn098"); 
// 접속 실패 시 메시지 나오게 하기 
if (mysqli_connect_errno($con)) 
{ echo "MySQL접속 실패: " . mysqli_connect_error(); } 

// 기본 클라이언트 문자 집합 설정하기 
mysqli_set_charset($con,"utf8"); 
// 쿼리문 실행, 결과를 res에 저장 
$res = mysqli_query($con, "select * from Route"); 
/*$res2 = mysql_query($con, "SELECT y_coord,x_coord INTO high_payed_emp_id
FROM (SELECT y_coord,x_coord  from Route)
WHERE rownum = 1");

$res3 = mysql_query($con, " select * from (select rownum as num, y_coord, x_coord from Route) a where a.num=2 ");
*/

// 결과를 배열로 변환하기 위한 변수 정의
$result = array();
// 쿼리문의 결과(res)를 배열형식으로 변환(result) 

while($row = mysqli_fetch_array($res)) 
{ array_push($result, array('id'=>$row[0],'node_name'=>$row[2],'address'=>$row[5],'x_coord1'=>$row[8],'y_coord1'=>$row[9],'x_coord2'=>$row[11],'y_coord2'=>$row[12])); } 
// 배열형식의 결과를 json으로 변환 
$a = json_encode(array("result"=>$result),JSON_UNESCAPED_UNICODE); 
echo $a;
// DB 접속 종료 
mysqli_close($con); 
?>


