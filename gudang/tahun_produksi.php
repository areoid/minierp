<!DOCTYPE HTML>
<!-- halaman tahun produksi -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Tahun Produksi - Gudang</title>

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">
		<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/dataTables.bootstrap.js"></script>
		<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				// hide warning
				$("#warning").hide();

				// cek
				$("#idTahunProduksi").blur(function(){
					var th = $("#idTahunProduksi").val();
					$.ajax({
						url: "../src/gudang/proses_tahun_produksi.php?action=cekTahun&cek="+th,
						cache: false,
						success: function(h){
							var hasil = $.trim(h);
							if(hasil == "0"){
								$('button[name="lanjut"]').removeAttr('disabled');
								$("#warning").hide();
							}
							else{
								$("#warning").show();
								$('button[name="lanjut"]').attr('disabled','disabled');
							}
						}
					});
				});

				// load list tahun produksi
				$("#divTabelTahunProduksi").load("../src/gudang/proses_tahun_produksi.php?action=listTahunProduksi");

				//div formTambahTahunProduksi hide
				$("#divFormTambahTahunProduksi").hide();

				//tambah diklik
				$("#idTambahTahunProduksi").click(function(){
					$("#idTambahTahunProduksi").hide();
					$("#divFormTambahTahunProduksi").slideDown(1500);
				});

				//cancel diklik
				$("#idCancelTambahTahunProduksi").click(function(){
					$("#idTahunProduksi").val("");
					$("#idKeterangan").val("");
					$("#divFormTambahTahunProduksi").slideUp(1000);
					$("#idTambahTahunProduksi").show();
				});

				// form tambah tahun submit
				$("#formTambahTahunProduksi").submit(function(){
					var vTahunProduksi = $("#idTahunProduksi").val();
					var vKeterangan = $("#idKeterangan").val();

					$.ajax({
						type: "POST",
						url: "../src/gudang/proses_tahun_produksi.php?action=tambah",
						data: {
							pTahunProduksi:vTahunProduksi,
							pKeterangan:vKeterangan
							},
						cache: false,
						success: function(hasilTambahTahunProduksi){
							alert(hasilTambahTahunProduksi);
							$('#idCancelTambahTahunProduksi').trigger('click');
							$("#divTabelTahunProduksi").load("../src/gudang/proses_tahun_produksi.php?action=listTahunProduksi");
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
			});

function editTahunProduksi(id){
	$.ajax({
    	type: "POST",
    	url: "../src/gudang/proses_tahun_produksi.php?action=editTahunProduksi",
    	data: {pId:id},
    	cache: false,
    	success: function(hasilEditTahunProduksi){
      		$("#dialogEditTahunProduksi").html(hasilEditTahunProduksi);
      		$("#dialogEditTahunProduksi").dialog({
        		position:['middle',20],
        		resizeable:true,
        		width:1000,
        		modal: true
      		});
  		}
 	});
}

function hapusTahunProduksi(id){
	$.ajax({
		type: "POST",
		url: "../src/gudang/proses_tahun_produksi.php?action=hapusTahunProduksi",
		data: {pId:id},
		cache: false,
		success: function(hasilHapus){
			alert(hasilHapus);
			$("#divTabelTahunProduksi").load("../src/gudang/proses_tahun_produksi.php?action=listTahunProduksi");
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
      <a class="navbar-brand" href="#">Area Gudang</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li class="active" class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang.php">Barang (Mentah)</a></li>
            <li><a href="supplier.php">Supplier</a></li>
            <li><a href="#">Tahun Produksi</a></li>
            <li><a href="satuan_barang.php">Satuan Barang</a></li>
            <li class="divider"></li>
            <li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah</a></li>
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="belanja.php">Belanja</a></li>
            <li><a href="pengeluaran_barang_ex.php">Permintaan Eksternal</a></li>
            <li><a href="konfirmasi_barang_mentah.php">Konfirmasi Barang Mentah</a></li>
            <li><a href="konfirmasi_barang_jadi.php">Konfirmasi Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-file"></span> Laporan <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="lap_jurnal.php">Laporan Barang Mentah</a></li>
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
		<button id="idTambahTahunProduksi" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Tahun
		</button>
		<div id="divFormTambahTahunProduksi">
			<div class="row">
				<div class="col-md-4"></div><!-- ruang kosong kiri -->
				<div class="col-md-4">
				<h3 align="center">Tambah Tahun Produksi</h3>
				<div class="thumbnail">
					<div class="caption">
				<form id="formTambahTahunProduksi">
					<label class="control-label" for="tahunproduksi">Tahun Produksi</label>
              		<input id="idTahunProduksi" type="text" class="form-control" autocomplete="off" placeholder="Tahun Produksi" required>
              		<span id="warning" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> Tahun sudah ada</span>
              		<br>
              		<label class="control-label" for="namabarang">Keterangan</label>
              		<textarea id="idKeterangan" class="form-control" placeholder="Keterangan" required></textarea>
              		<br>
              		<div class="pull-right">
              		<button id="idCancelTambahTahunProduksi" type="button" class="btn btn-default">
						<span class="glyphicon glyphicon-remove"></span> Cancel
					</button>
              		<button type="submit" name="lanjut" class="btn btn-primary">
              			<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
              		</button>
              		</div>
              		<br>
              		<br>
				</form>
					</div>
				</div>
				</div>
				
				<div class="col-md-4"></div><!-- ruang kosong kanan -->
			</div>
		</div>
		<br>
		<br>
		<div id="divTabelTahunProduksi">

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
<div id="dialogEditTahunProduksi">
	
</div>
</body>
</html>