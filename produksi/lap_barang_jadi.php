<!DOCTYPE HTML>
<?php
include "../src/produksi/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Lap. Transaksi Barang (Jadi) - Produksi</title>

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {

				// form filter di klik
				$("#formFilter").submit(function() {
					var vBulanA = $("#idBulanA").val();
					var vTahunA = $("#idTahunA").val();
					var vBulanB = $("#idBulanB").val();
					var vTahunB = $("#idTahunB").val();
					
					// cek form
					if(vBulanA == "--" || vTahunA == "--" || vBulanB == "--" || vTahunB == "--"){
						alert("Tolong dipilih dengan benar");
						return false;
					}

					//alert("b a "+vBulanA+" t a "+vTahunA+" vBulanb "+vBulanB+" vTahunB "+vTahunB);
					
					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_lap_barang_jadi.php?action=filter",
						data: {
							pBulanA:vBulanA,
							pTahunA:vTahunA,
							pBulanB:vBulanB,
							pTahunB:vTahunB
						},
						cache: false,
						success: function(hasilFilter){
							$("#divTabelLapBarang").html(hasilFilter);
							//alert(hasilFilter);
						}
					});

					return false;
				});

				// auto load tabel jurnal laporan barang
				$("#divTabelLapBarang").load("../src/gudang/proses_lap_barang_jadi.php?action=jurnalBarang");

				// auto tahun
				$(".classTahun").load("../src/gudang/proses_lap_barang_jadi.php?action=autoTahun");

				// untuk logout
				$("#idGudangKeluar").click(function(){
					$.ajax({
						url: "../src/gudang/clear_session.php",
						cache: false,
						success: function(hasilLogout){
							alert(hasilLogout);
							window.location.reload();
						}
					});
				});

			} );
		</script>
	</head>
	<body>
<div class="container">
<!-- navbar -->
<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Area Produksi</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang_jadi.php">Barang Jadi</a></li>
            <li class="divider"></li>
            <li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah</a></li>
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="permintaan_barang.php">Permintaan Barang Mentah</a></li>
            <li><a href="pengiriman_barang.php">Pengiriman Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Laporan Barang Jadi</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav pull-right">
		<li><a id="idGudangKeluar" href="#"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navbar -->

<!-- kotak tengah -->
<div class="thumbnail">
	<div class="caption">
		<div class="pull-right">
			<form id="formFilter" class="form-inline" role="form">
				Mulai : 
				<select id="idBulanA" class="form-control">
			  		<option value="--">Bulan</option>
					<option value="1">Jan</option>
					<option value="2">Feb</option>
					<option value="3">Mar</option>
					<option value="4">Apr</option>
					<option value="5">Mei</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Agu</option>
					<option value="9">Sep</option>
					<option value="10">Okt</option>
					<option value="11">Nov</option>
					<option value="12">Des</option>
			 	</select>
			 	<select id="idTahunA" class="form-control classTahun">
					<option value="1">--</option>
			 	</select>
			 	Sampai :
			  	<select id="idBulanB" class="form-control">
			  		<option value="--">Bulan</option>
					<option value="1">Jan</option>
					<option value="2">Feb</option>
					<option value="3">Mar</option>
					<option value="4">Apr</option>
					<option value="5">Mei</option>
					<option value="6">Jun</option>
					<option value="7">Jul</option>
					<option value="8">Agu</option>
					<option value="9">Sep</option>
					<option value="10">Okt</option>
					<option value="11">Nov</option>
					<option value="12">Des</option>
			 	</select>
			 	<select id="idTahunB" class="form-control classTahun">
					<option value="1">--</option>
			 	</select>
			  	<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Filter</button>
			</form> 
		</div>
		<br>
		<br>
		<div id="divTabelLapBarang">

		</div>
	</div>
</div><!-- thumbnail -->

<nav class="navbar navbar-inverse" role="navigation">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse .footer" id="bs-example-navbar-collapse-1">

    </div><!-- /.navbar-collapse -->
    <br>
    <div align="center">
    	<!--<div class="thumbnail" style="background-color: transparent">
			<div class="caption" style="background-color: transparent">-->
    			<img width="20%" src="../images/logo-naratel.png" />
    			<h6><span style="color: #fff"> Copyright © 2014 | PT.Naraya Telematika </span></h6>
    		
    </div>
</nav>

</div><!-- /container -->

	</body>
</html>