<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width",initial-scale="1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>태산팩VRP2</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/태산팩VRP.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- datepicker 한국어로 -->
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/i18n/datepicker-ko.js"></script>
<script
	src="https://apis.openapi.sk.com/tmap/jsv2?version=1&appKey=l7xx74849871c4854815abb6f18dda2baf48"></script>

<script>
    $(function() {
                
            
                //오늘 날짜를 출력
                $("#today").text(new Date().toLocaleDateString());

                //datepicker 한국어로 사용하기 위한 언어설정
                $.datepicker.setDefaults($.datepicker.regional['ko']); 
                
                // 시작일(fromDate)은 종료일(toDate) 이후 날짜 선택 불가
                // 종료일(toDate)은 시작일(fromDate) 이전 날짜 선택 불가

                //시작일.
                $('#txtFrom').datepicker({
                    showOn: "both",                     // 달력을 표시할 타이밍 (both: focus or button)
                               // 버튼 이미지만 표시할지 여부
                    buttonText: "날짜선택",             // 버튼의 대체 텍스트
                    dateFormat: "yy-mm-dd",             // 날짜의 형식
                    changeMonth: true                 // 월을 이동하기 위한 선택상자 표시여부
                    //minDate: 0,                
                });
            });


</script>
<script type="text/javascript">
	var map, marker_s, marker_e, marker_p;
	var drawInfoArr = [];
	var resultMarkerArr = [];
	var resultdrawArr = [];
	function initTmap() {

		// 1. 지도 띄우기
		map = new Tmapv2.Map("map_div", {
			center : new Tmapv2.LatLng(37.3525179, 127.2498965),
			width : "100%",
			height : "500px",
			zoom : 11,
			zoomControl : true,
			scrollwheel : true
		});

		// 2. 시작, 도착 심볼찍기
		// 시작
		marker_s = new Tmapv2.Marker(
				{
					position : new Tmapv2.LatLng(37.3525179, 127.2498965),
					icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_r_m_s.png",
					iconSize : new Tmapv2.Size(24, 38),
					map : map
				});

		// 도착
		marker_e = new Tmapv2.Marker(
				{
					position : new Tmapv2.LatLng(37.79402827, 127.07959129999999),
					icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_r_m_e.png",
					iconSize : new Tmapv2.Size(24, 38),
					map : map
				});

		// 3. 경유지 심볼 찍기
		marker_p = new Tmapv2.Marker(
				{
					position : new Tmapv2.LatLng(37.66010359, 127.07372420000002),
					icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_p.png",
					iconSize : new Tmapv2.Size(24, 38),
					map : map
				});

		marker_p = new Tmapv2.Marker(
				{
					position : new Tmapv2.LatLng(37.654015, 127.06247250000001),
					icon : "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_b_m_p.png",
					iconSize : new Tmapv2.Size(24, 38),
					map : map
				});

		// 4. 경로탐색 API 사용요청
		var routeLayer;
		$("#btn_select")
				.click(
						function() {

							if (routeLayer) {
								map.removeLayer(routeLayer);
							}

							var predictionType = $(
									':radio[name="time_toggle"]:checked').val();
							console.log(predictionType);

							var year = $("#year").val();
							var month = $("#month").val();
							var day = $("#day").val();
							var hour = $("#hour").val();
							var min = $("#min").val();
							var predictionTime = year + "-" + month + "-" + day
									+ "T" + hour + ":" + min + ":00+0900";
							console.log(predictionTime);

							var searchOption = $("#selectLevel").val();

							var headers = {};
							headers["appKey"] = "l7xx74849871c4854815abb6f18dda2baf48";
							headers["Content-Type"] = "application/json";

							var urlStr = "https://apis.openapi.sk.com/tmap/routes/prediction?version=1&reqCoordType=WGS84GEO&resCoordType=EPSG3857&format=json";

							var params = JSON.stringify({
								"routesInfo" : {
									"departure" : {
										"name" : "test1",
										"lon" : "127.2498965",
										"lat" : "37.3525179"
									},
									"destination" : {
										"name" : "test2",
										"lon" : "127.07959129999999",
										"lat" : "37.79402827"
									},
									"predictionType" : predictionType,
									"predictionTime" : String(predictionTime),
									"wayPoints" : {
										"wayPoint" : [ {
											"lon" : "127.07372420000002",
											"lat" : "37.66010359"
										}, {
											"lon" : "127.06247250000001",
											"lat" : "37.654015"
										} ]
									},
									"searchOption" : String(searchOption)
								}
							});
							$
									.ajax({
										method : "POST",
										url : urlStr,
										headers : headers,
										async : false,
										data : params,
										success : function(response) {
											var resultData = response.features;

											var resultProperties = resultData[0].properties;

											var innerHtml = "";

											var tDistance = "총 거리 : "
													+ (resultProperties.totalDistance/1000).toFixed(1)
													+ "km, ";
											var tTime = "총 시간 : "
													+ (resultProperties.totalTime/60).toFixed(0)
													+ "분, ";
											var tFare = "총 요금 : "
													+ resultProperties.totalFare
													+ "원, ";
											var taxiFare = "예상 택시 요금 : "
													+ resultProperties.taxiFare
													+ "원";

											$("#result").text(
													tDistance + tTime + tFare
															+ taxiFare);

											//기존 그려진 라인 & 마커가 있다면 초기화
											if (resultdrawArr.length > 0) {
												for ( var i in resultdrawArr) {
													resultdrawArr[i]
															.setMap(null);
												}
												resultdrawArr = [];
											}

											if (resultMarkerArr.length > 0) {
												for ( var i in resultMarkerArr) {
													resultMarkerArr[i]
															.setMap(null);
												}
												resultMarkerArr = [];
											}
											
											drawInfoArr = [];
											var lineYn = false;

											//그리기
											//for문 [S]
											for ( var i in resultData) {
												var geometry = resultData[i].geometry;
												var properties = resultData[i].properties;

												if (geometry.type == "LineString") {
													for ( var j in geometry.coordinates) {
														// 경로들의 결과값들을 포인트 객체로 변환 
														var latlng = new Tmapv2.Point(
																geometry.coordinates[j][0],
																geometry.coordinates[j][1]);
														// 포인트 객체를 받아 좌표값으로 변환
														var convertPoint = new Tmapv2.Projection.convertEPSG3857ToWGS84GEO(
																latlng);
														// 포인트객체의 정보로 좌표값 변환 객체로 저장
														var convertChange = new Tmapv2.LatLng(
																convertPoint._lat,
																convertPoint._lng);
														// 배열에 담기
														if(lineYn){
							                                    drawInfoArr.push(convertChange);
							                                }
													}
												} else {
													
													if(properties.pointType == "S" || properties.pointType == "E" || properties.pointType == "N" ){
						                                 lineYn = true;
						                            }else{
						                            	 lineYn = false;
						                            }

													var markerImg = "";
													var pType = "";

													if (properties.pointType == "S") { //출발지 마커
														markerImg = "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_r_m_s.png";
														pType = "S";
													} else if (properties.pointType == "E") { //도착지 마커
														markerImg = "http://tmapapis.sktelecom.com/upload/tmap/marker/pin_r_m_e.png";
														pType = "E";
													} else { //각 포인트 마커
														markerImg = "http://topopen.tmap.co.kr/imgs/point.png";
														pType = "P"
													}

													// 경로들의 결과값들을 포인트 객체로 변환 
													var latlon = new Tmapv2.Point(
															geometry.coordinates[0],
															geometry.coordinates[1]);
													// 포인트 객체를 받아 좌표값으로 다시 변환
													var convertPoint = new Tmapv2.Projection.convertEPSG3857ToWGS84GEO(
															latlon);

													var routeInfoObj = {
														markerImage : markerImg,
														lng : convertPoint._lng,
														lat : convertPoint._lat,
														pointType : pType
													};

													// Marker 추가
													addMarkers(routeInfoObj);
												}
											}//for문 [E]
											drawLine(drawInfoArr);
										}
									});

						});

	}

	function pad(n, width) {
		n = n + '';
		return n.length >= width ? n : new Array(width - n.length + 1)
				.join('0')
				+ n;
	}

	function addComma(num) {
		var regexp = /\B(?=(\d{3})+(?!\d))/g;
		return num.toString().replace(regexp, ',');
	}

	function drawLine(arrPoint) {
		var polyline_;

		polyline_ = new Tmapv2.Polyline({
			path : arrPoint,
			strokeColor : "#DD0000",
			strokeWeight : 6,
			map : map
		});
		resultdrawArr.push(polyline_);
	}

	function addMarkers(infoObj) {
		var size = new Tmapv2.Size(24, 38);//아이콘 크기 설정합니다.

		if (infoObj.pointType == "P") { //포인트점일때는 아이콘 크기를 줄입니다.
			size = new Tmapv2.Size(8, 8);
		}

		marker_p = new Tmapv2.Marker({
			position : new Tmapv2.LatLng(infoObj.lat, infoObj.lng),
			icon : infoObj.markerImage,
			iconSize : size,
			map : map
		});

		resultMarkerArr.push(marker_p);
	}
</script>
  </head>
     
  <body onload="initTmap();">
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
              <a class="nav-link" href="classification.php">배송분류하기</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="previous.html">이전배송분류</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="route.html">경로확인</a>
            </li>
          </ul>
        </div>
      </div>    
    </nav>
    <div class="row" style="margin:0px; padding:10px;">
      <div class="col-md-8">
        <div id="layout_wrap">
          <section class="in_section">
            <div class="sub">
              <div class="time_toggle_wrap">
                <div class="time_toggle">
                  <input checked id="tt_st" name="time_toggle" type="radio"
                    value="arrival"> <label for="tt_st">출발시간</label> <input
                    id="tt_ed" name="time_toggle" type="radio" value="departure">
                  <label for="tt_ed">도착시간</label>
                </div>
                <div class="clear"></div>
              </div>
      
              <div class="ft_area m">
                <div class="ft_select_wrap">
                  <div class="ft_select">
                    <select id="year">
                      <option value="2017" selected="selected">2017</option>
                      <option value="2018">2018</option>
                      <option value="2019">2019</option>
                      <option value="2020">2020</option>
                      <option value="2021">2021</option>
                      <option value="2022">2022</option>
                    </select> <label>년</label> <select id="month">
                      <option value="01" selected="selected">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                    </select> <label>월</label> <select id="day">
                      <option value="01" selected="selected">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
                      <option value="31">31</option>
                    </select> <label>일</label> <select id="hour">
                      <option value="00" selected="selected">00</option>
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                    </select> <label>시</label> <select id="min">
                      <option value="00" selected="selected">00</option>
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
                      <option value="31">31</option>
                      <option value="32">32</option>
                      <option value="33">33</option>
                      <option value="34">34</option>
                      <option value="35">35</option>
                      <option value="36">36</option>
                      <option value="37">37</option>
                      <option value="38">38</option>
                      <option value="39">39</option>
                      <option value="40">40</option>
                      <option value="41">41</option>
                      <option value="42">42</option>
                      <option value="43">43</option>
                      <option value="44">44</option>
                      <option value="45">45</option>
                      <option value="46">46</option>
                      <option value="47">47</option>
                      <option value="48">48</option>
                      <option value="49">49</option>
                      <option value="50">50</option>
                      <option value="51">51</option>
                      <option value="52">52</option>
                      <option value="53">53</option>
                      <option value="54">54</option>
                      <option value="55">55</option>
                      <option value="56">56</option>
                      <option value="57">57</option>
                      <option value="58">58</option>
                      <option value="59">59</option>
                    </select> <label>분</label> <select id="selectLevel">
                      <option value="00" selected="selected">교통최적+추천</option>
                      <option value="01">교통최적+무료우선</option>
                      <option value="02">교통최적+최소시간</option>
                      <option value="03">교통최적+초보</option>
                      <option value="04">교통최적+고속도로우선</option>
                      <option value="10">최단거리+유/무료</option>
                    </select>
                    <button id="btn_select">적용하기</button>
                  </div>
                </div>
                <div class="clear"></div>
              </div>
      
              <div id="map_wrap" class="map_wrap">
                <div id="map_div"></div>
              </div>
              <div class="map_act_btn_wrap clear_box"></div>
              <p id="result"></p>
              <br />
            </div>
          </section>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card" >
              <div class="card-header card-header-primary">
                <div class="row">
                  <div class="col-md-6" >
                  <h4 class="card-title ">배달여부</h4>
                  <input type="text" id="txtFrom" name="Fromdate"/>
                  <input type="submit" id="search" name="Search" value="검색" />
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                    <th>
                        순서
                      </th>
                      <th>
                        거래처
                      </th>
                      <th>
                        주소
                      </th>
                      <th>
                        배송여부
                      </th>
                      
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          1
                        </td>
                        <td>
                          왕림족발보쌈
                        </td>
                        <td>
                          경기 광주시 오포읍 능평리 172-2
                        </td>
                        <td>
                          <button type="submit" class="btn btn-primary pull-right">배송완료 </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          2
                        </td>
                        <td>
                          왕림족발보쌈
                        </td>
                        <td>
                          경기 광주시 오포읍 능평리 172-2
                        </td>
                        <td>
                          <button type="submit" class="btn btn-primary pull-right">배송완료 </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          3
                        </td>
                        <td>
                          왕림족발보쌈
                        </td>
                        <td>
                          경기 광주시 오포읍 능평리 172-2
                        </td>
                        <td>
                          <button type="submit" class="btn btn-primary pull-right">배송완료 </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          4
                        </td>
                        <td>
                          왕림족발보쌈
                        </td>
                        <td>
                          경기 광주시 오포읍 능평리 172-2
                        </td>
                        <td>
                          <button type="submit" class="btn btn-primary pull-right">배송완료 </button>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          5
                        </td>
                        <td>
                          왕림족발보쌈
                        </td>
                        <td>
                          경기 광주시 오포읍 능평리 172-2
                        </td>
                        <td>
                          <button type="submit" class="btn btn-primary pull-right">배송완료 </button>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
      </div>
    </div>
      
       

  
    <script src="js/bootstrap.js"></script>
  </body>
</html>