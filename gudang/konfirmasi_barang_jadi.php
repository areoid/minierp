<!DOCTYPE HTML>
<!-- halaman konfirmasi barang jadi gudang -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Konfirmasi Barang Jadi - Gudang</title>

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
				$("#divListMenunggu").load('../src/gudang/proses_konfirmasi_barang_jadi.php?action=listMenunggu');
				$("#divListTerkonfirmasi").load('../src/gudang/proses_konfirmasi_barang_jadi.php?action=listTerkonfirmasi');

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

function printTerkonfirmasi(kode){
	var url = "../src/gudang/proses_konfirmasi_barang_jadi.php?action=printTerkonfirmasi&kode="+kode;
  	var win=window.open(url, '_blank');
  	win.focus();
}

function lihatTerkonfirmasi(kode){
	$.ajax({
		url: "../src/gudang/proses_konfirmasi_barang_jadi.php?action=lihatTerkonfirmasi&pKode="+kode,
		cache: false,
		success: function(hasilLihat){
			$("#dialogLihatTerkonfirmasi").html(hasilLihat);
			$("#dialogLihatTerkonfirmasi").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function lihatPengiriman(kode){
	$.ajax({
		url: "../src/gudang/proses_konfirmasi_barang_jadi.php?action=lihatPermintaan&pKode="+kode,
		cache: false,
		success: function(hasilLihat){
			$("#dialogLihatPengiriman").html(hasilLihat);
			$("#dialogLihatPengiriman").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true,
				buttons: {
					"Selesai": function() {
						var cek = $(".cek").html();
						if(cek == "0"){
							alert('Maaf, belum ada yang dikonfirmasi');
							return false;
						}
						var vNoKirim = $("#idNoKirim").html();
						$.ajax({
							type: "GET",
							url: "../src/gudang/proses_konfirmasi_barang_jadi.php?action=konfirmasiSelesai&pNoKirim="+vNoKirim,
							cache: false,
							success: function(hasilKonfirmasiSelesai){
								alert(hasilKonfirmasiSelesai);
								$("#dialogLihatPengiriman").dialog("close");
								$("#divListMenunggu").load('../src/gudang/proses_konfirmasi_barang_jadi.php?action=listMenunggu');
								$("#divListTerkonfirmasi").load('../src/gudang/proses_konfirmasi_barang_jadi.php?action=listTerkonfirmasi');
							}
						});
					},
					Cancel: function() {
						var vNoKirim = $("#idNoKirim").html();
						$.ajax({
							type: "GET",
							url: "../src/gudang/proses_konfirmasi_barang_jadi.php?action=konfirmasiCancel&pNoKirim="+vNoKirim,
							cache: false,
							success: function(hasil){
								alert(hasil);
							}
						});
						$(this).dialog("close");
					}
				}
			});
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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang.php">Barang (Mentah)</a></li>
            <li><a href="barang_jadi.php">Barang (Jadi)</a></li>
            <li><a href="supplier.php">Supplier</a></li>
            <li><a href="tahun_produksi.php">Tahun Produksi</a></li>
            <li><a href="satuan_barang.php">Satuan Barang</a></li>
            <li class="divider"></li>
            <li><a href="stok_barang_mentah_gudang.php">Stok Barang Mentah</a></li>            
            <li><a href="stok_barang_jadi.php">Stok Barang Jadi</a></li>
          </ul>
        </li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="belanja.php">Belanja</a></li>
            <li><a href="pengeluaran_barang_ex.php">Permintaan Eksternal</a></li>
            <li><a href="konfirmasi_barang_mentah.php">Konfirmasi Barang Mentah</a></li>
            <li><a href="#">Konfirmasi Barang Jadi</a></li>
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
		<div id="divListMenunggu">

		</div>
		<br>
		<legend></legend>
		<div id="divListTerkonfirmasi">

		</div>
	</div>
</div>

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

<div id="dialogLihatPengiriman">
</div>
<div id="dialogLihatTerkonfirmasi">
</div>

	</body>
</html>