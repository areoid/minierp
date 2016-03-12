<!DOCTYPE HTML>
<!-- halaman supplier gudang -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Supplier - Gudang</title>

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
        //hide form tambah supplier
        $("#divFormTambahSupplier").hide();

        //tambah diklik
        $("#idTambahSupplier").click(function(){
          //panggil fungsi autokodesupplier
          autoKodeSupplier();
          $("#idTambahSupplier").hide();
          $("#divFormTambahSupplier").slideDown(1500);
        });

        //cancel diklik
        $("#idCancelTambahSupplier").click(function(){
          $("#idKodeSupplier").val("");
          $("#idNamaSupplier").val("");
          $("#idAlamatSupplier").val("");
          $("#divFormTambahSupplier").slideUp(1000);
          $("#idTambahSupplier").show();
        });

        //load list supplier
        $("#divTabelListSupplier").load("../src/gudang/proses_supplier.php?action=listSupplier");

        // form submit
        $("#formTambahSupplier").submit(function(){
          var vKodeSupplier = $("#idKodeSupplier").val();
          var vNamaSupplier = $("#idNamaSupplier").val();
          var vAlamatSupplier = $("#idAlamatSupplier").val();

          //alert(vKodeSupplier+" "+vNamaSupplier+" "+vAlamatSupplier);

          $.ajax({
            type: "POST",
            url: "../src/gudang/proses_supplier.php?action=tambahSupplier",
            data:{pKodeSupplier:vKodeSupplier,
                  pNamaSupplier:vNamaSupplier,
                  pAlamatSupplier:vAlamatSupplier},
            cache: false,
            success: function(hasilTambahSupplier){
              alert(hasilTambahSupplier);
              $('#idCancelTambahSupplier').trigger('click');
              $("#divTabelListSupplier").load("../src/gudang/proses_supplier.php?action=listSupplier");
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

// fungsi pembuat kode supplier
function autoKodeSupplier(){
  $.ajax({
    url: "../src/gudang/proses_supplier.php?action=kodeSupplier",
    cache: false,
    success: function(hasilAutoKodeSupplier){
      $("#idKodeSupplier").val(hasilAutoKodeSupplier);
    }
  });

}

function editSupplier(kode){
  $.ajax({
    type: "POST",
    url: "../src/gudang/proses_supplier.php?action=editSupplier",
    data: {pKode:kode},
    cache: false,
    success: function(hasilEditSupplier){
      $("#dialogEditSupplier").html(hasilEditSupplier);
      $("#dialogEditSupplier").dialog({
        position:['middle',20],
        resizeable:true,
        width:1000,
        modal: true
      });
    }
  });
}

function hapusSupplier(kode){
  $.ajax({
    type: "POST",
    url: "../src/gudang/proses_supplier.php?action=hapusSupplier",
    data: {pKode:kode},
    cache: false,
    success: function(hasilHapusSupplier){
      alert(hasilHapusSupplier);
      $("#divTabelListSupplier").load("../src/gudang/proses_supplier.php?action=listSupplier");
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
            <li><a href="#">Supplier</a></li>
            <li><a href="tahun_produksi.php">Tahun Produksi</a></li>
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
            <li><a href="konfirmasi_barnag_jadi.php">Konfirmasi Barang Jadi</a></li>
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
		<button id="idTambahSupplier" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Supplier
		</button>
		<div id="divFormTambahSupplier">
			<div class="row">
				<div class="col-md-4"></div><!-- ruang kosong kiri -->
				<div class="col-md-4">
				<h3 align="center">Tambah Supplier</h3>
        <div class="thumbnail">
          <div class="caption">
				<form id="formTambahSupplier">
					<label class="control-label" for="kodebarang">Kode Supplier</label>
              		<input id="idKodeSupplier" disabled type="text" class="form-control" autocomplete="off" placeholder="Kode Barang" required>
              		<br>
              		<label class="control-label" for="namabarang">Nama Supplier</label>
              		<input id="idNamaSupplier" type="text" class="form-control" autocomplete="off" placeholder="Nama Barang" required>
              		<br>
              		<label class="control-label" for="hargabeli">Alamat</label>
              		<textarea id="idAlamatSupplier" type="text" class="form-control" autocomplete="off" placeholder="Alamat Supplier" required></textarea>
              		<br>
              		<div class="pull-right">
              		<button id="idCancelTambahSupplier" type="button" class="btn btn-default">
        						<span class="glyphicon glyphicon-remove"></span> Cancel
        					</button>
              		<button type="submit" class="btn btn-primary">
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
		<div id="divTabelListSupplier">

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
<div id="dialogEditSupplier">

</div>
</body>
</html>