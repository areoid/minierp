<!DOCTYPE HTML>
<!-- halaman barang jadi gudang -->
<?php
include "../src/produksi/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Barang (Jadi) - Produksi</title>

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/pageleaves.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				// load list barang
				$("#divListBarang").load("../src/produksi/proses_barang_jadi.php?action=listBarang");

				// load satuan
				$("#idSatuan").load("../src/gudang/proses_barang.php?action=listSatuan");

				//div formbarang hide
				$("#divFormTambahBarang").hide();

				//tambah diklik
				$("#idTambahBarang").click(function(){
					$("#idTambahBarang").hide();
					$("#divFormTambahBarang").slideDown(1500);
				});

				// valid
				$("#warning").hide();
				$("#idKodeBarang").blur(function(){
					var cek = $("#idKodeBarang").val();
					$.ajax({
						url: "../src/produksi/proses_barang_jadi.php?action=cekKode&pKode="+cek,
						cache: false,
						success: function(h){
							var hasil = $.trim(h);
							if(hasil == "0"){
								$('button[name="simpan"]').removeAttr('disabled');
								$("#warning").hide();
							}
							else{
								$("#warning").show();
								$('button[name="simpan"]').attr('disabled','disabled');
							}
						}
					});
				});

				//cancel diklik
				$("#idCancelTambahBarang").click(function(){
					$("#idKodeBarang").val("");
					$("#idNamaBarang").val("");
					$("#idSatuan").load("../src/gudang/proses_barang.php?action=listSatuan");
					$("#idStokAwal").val("");
					$("#warning").hide();
					$("#divFormTambahBarang").slideUp(1000);
					$("#idTambahBarang").show();
				});

				//auto uppercase
				$('#idKodeBarang').keyup(function(){
					this.value = this.value.toUpperCase();
				});

				// form tambah barang submit
				$("#formTambahBarang").submit(function(){
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vSatuan = $("#idSatuan").val();
					var vStokAwal = $("#idStokAwal").val();

					if(vSatuan == "--"){
						alert("Harus diisi benar !!");
						return false;
					}

					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_barang_jadi.php?action=tambah",
						data: {
							pKodeBarang:vKodeBarang, 
							pNamaBarang:vNamaBarang,
							pSatuan:vSatuan, 
							pStokAwal:vStokAwal
						},
						cache: false,
						success: function(hasilTambahBarang){
							$('#idCancelTambahBarang').trigger('click');
							$("#divListBarang").load("../src/produksi/proses_barang_jadi.php?action=listBarang");
						}
					});
					
					return false;
				});

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
				$(document).on('barang', function() {
			        alert("Oh no! Please don't leave me!");
			        // do sth. else
			    });
			   
			});

function editBarang(kode){
	$.ajax({
    	type: "POST",
    	url: "../src/produksi/proses_barang_jadi.php?action=editBarang",
    	data: {pKode:kode},
    	cache: false,
    	success: function(hasilEditBarang){
      		$("#dialogEditBarang").html(hasilEditBarang);
      		$("#dialogEditBarang").dialog({
        		position:['middle',20],
        		resizeable:true,
        		width:1000,
        		modal: true
      		});
  		}
 	});
}

function lihatBarang(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_barang_jadi.php?action=lihatBarang",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatBarang){
			$("#dialogLihatBarang").html(hasilLihatBarang);
      		$("#dialogLihatBarang").dialog({
        		position:['middle',20],
        		resizeable:true,
        		width: 600,
        		modal: true
      		});
		}
	});
}

function deleteBarang(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_barang_jadi.php?action=deleteBarang",
		data: {pKode:kode},
		cache: false,
		success: function(hasilDeleteBarang){
			alert(hasilDeleteBarang);
			$("#divListBarang").load("../src/produksi/proses_barang_jadi.php?action=listBarang");
		}
	});
}

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
        <li class=""><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="dropdown active">
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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="lap_barang_jadi.php">Laporan Barang Jadi</a></li>
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
		<button id="idTambahBarang" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Barang Jadi
		</button>
		<div id="divFormTambahBarang">
			<div class="row">
				<div class="col-md-4"></div><!-- ruang kosong kiri -->
				<div class="col-md-4">
				<h3 align="center">Tambah Barang Jadi</h3>
				<form id="formTambahBarang">
					<label class="control-label" for="kodebarang">Kode Barang</label>
              		<input id="idKodeBarang" type="text" class="form-control" autocomplete="off" placeholder="Kode Barang" required>
              		<span id="warning" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> Kode barang sudah ada</span>
              		<br>
              		<label class="control-label" for="namabarang">Nama Barang</label>
              		<input id="idNamaBarang" type="text" class="form-control" autocomplete="off" placeholder="Nama Barang" required>
              		<br>
              		<label class="control-label" for="kodebarang">Satuan</label>
              		<select id="idSatuan" class="form-control">
					</select> 
              		<br>
              		<label class="control-label" for="hargabeli">Stok Awal</label>
              		<input id="idStokAwal" type="text" class="form-control" autocomplete="off" placeholder="Stok Awal" required>
              		<br>
              		<div class="pull-right">
              		<button id="idCancelTambahBarang" type="button" class="btn btn-default">
						<span class="glyphicon glyphicon-remove"></span> Cancel
					</button>
              		<button type="submit" name="simpan" class="btn btn-primary">
              			<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
              		</button>
              		</div>
				</form>
				</div>
				
				<div class="col-md-4"></div><!-- ruang kosong kanan -->
			</div>
		</div>
		<br>
		<br>
		<div id="divListBarang">
			
		</div>
		
	</div>
</div>
<!-- /kotak tengah -->

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
<div id="dialogEditBarang">
	
</div>
<div id="dialogLihatBarang">
	
</div>
</body>
</html>