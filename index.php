<!DOCTYPE HTML>
<!-- halaman login ongkowijoyo-->
<?php
include "src/login/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Naratel Project</title>

		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.css">
		<script type="text/javascript" language="javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$("#formLogin").submit(function(){
					var vUsername = $("#idUsername").val();
					var vPassword = $("#idPassword").val();
					
					$.ajax({
						type: "POST",
						url: "src/login/proses_login.php",
						data:{ postUsername:vUsername, postPassword:vPassword },
						cache: false,
						success: function(hasilLogin){
							var cek = $.trim(hasilLogin);
							if(cek == 'gudang'){
								window.location.href="gudang";
							}
							else if(cek == 'produksi'){
								window.location.href="produksi";	
							}
							else if(cek == 'sales'){
								window.location.href="sales";	
							}
							else{
								alert("Login Gagal !!");	
							}
						}
					});

					return false;
				});
			});
		</script>
		<style type="text/css">
		body {
		   background-image: url('../images/bg-fix.jpg') !important; 
		   background-position:fixed;
		}
		</style>
	</head>
	<body class="clearfix" background="../images/bg-fix.jpg">
		<div class="pull-right">
			<!--<button class="btn btn-primary">Login</button>-->
		</div>
		<div class="container">

<div class="row">
	<div class="col-md-4">
	</div><!-- ruang kiri -->
	<div class="col-md-4">
		<br>
		<br>
		<br>

		<div class="thumbnail">
			<h3 align="center"> Login </h3>
			<legend></legend>
			<div class="caption">
				<form id="formLogin">
					<label class="control-label" for="username">Username</label>
					<input id="idUsername" type="text" class="form-control" autocomplete="off" placeholder="Username" required>
					<br>
					<label class="control-label" for="password">Password</label>
					<input id="idPassword" type="password" class="form-control" autocomplete="off" placeholder="Password" required>
					<br>
					<button type="submit" class="btn btn-primary">Login</button>
				</form>
			</div>
		</div>
	</div><!-- ruang tengah -->
	<div class="col-md-4">
	</div><!-- ruang kanan -->
</div>

		</div><!-- /div container -->
	</body>
</html>
