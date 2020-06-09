<!--
this is header file which is visible in registration and login page.
-->
<?php
include_once('link.php');

?>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        
        <a class="navbar-brand" href="index.html">태산팩VRP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="nav navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="http://tspack.co.kr/" target="_blank">태산팩 홈페이지<span class="sr-only"></span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="javascript:alert('로그인을 하세요.');">주문내역보기</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:alert('로그인을 하세요.');">이전배송분류</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:alert('로그인을 하세요.');">경로확인</a>
            </li>
		  </ul>
		  <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">접속하기<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="Login.php">로그인</a></li>
                <li><a class="dropdown-item" href="Registration.php">회원가입</a></li>
              </ul>
            </li>
            
          </ul>
        </div>
      </div>    
    </nav>