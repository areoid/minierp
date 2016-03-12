<!DOCTYPE HTML>
<!-- halaman satuan barang gudang -->
<?php
include "../src/gudang/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Satuan - Gudang</title>

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
  // hide form
  $("#divFormTambahSatuan").hide();

  // tambah di klik
  $("#idTambahSatuan").click(function(){
     $("#idTambahSatuan").hide();
     $("#divFormTambahSatuan").slideDown(1500);
  });

  // cancel
  $("#btnCancelSatuan").click(function(){
    $("#idKeterangSatuan").val("");
    $("#idNamaSatuan").val("");
    $("#divFormTambahSatuan").slideUp(1000);
    $("#idTambahSatuan").show();
  });

  // form tambah satuan disubmit
	$("#formTambahSatuan").submit(function(){
		var vNamaSatuan = $("#idNamaSatuan").val();
    var vNilaiSatuan =  $("#idNilaiSatuan").val();
		var vKeteranganSatuan = $("#idKeterangSatuan").val();
    var vSatuanKecil = $("#idSatuanKecil").val();

		$.ajax({
			type: "POST",
			url: "../src/gudang/proses_satuan_barang.php?action=tambahSatuan",
			data: {
				pNamaSatuan:vNamaSatuan,
        pNilaiSatuan:vNilaiSatuan,
        pSatuanKecil:vSatuanKecil,
				pKeteranganSatuan:vKeteranganSatuan
			},
			cache: false,
			success: function(hasilTambahSatuan){
				alert("Tambah "+hasilTambahSatuan);
        if(hasilTambahSatuan == "OK"){
          alert("oyeee");
        }
        $('#btnCancelSatuan').trigger('click');
        $("#divTabelListSatuan").load("../src/gudang/proses_satuan_barang.php?action=listSatuan");
			}
		});

		return false;
	}); 
  // end form

  // load tabel satuan
  $("#divTabelListSatuan").load("../src/gudang/proses_satuan_barang.php?action=listSatuan");
  // end load

});

function angka(e) {
  if (!/^[0-9]+$/.test(e.value)) {
    e.value = e.value.substring(0,e.value.length-1);
   }
}

// fungsi untuk edit
function editSatuan(id){
  $.ajax({
    type: "POST",
    url: "../src/gudang/proses_satuan_barang.php?action=editSatuan",
    data: {pId:id},
    cache: false,
    success: function(hasilEditSatuan){
      $("#dialogEditSatuan").html(hasilEditSatuan);
      $("#dialogEditSatuan").dialog({
        position:['middle',20],
        resizeable:true,
        width:500,
        modal: true
      });
    }
  });
}
// end fungsi edit

// fungsi untuk hapus
function hapusSatuan(id){
  $.ajax({
    url: "../src/gudang/proses_satuan_barang.php?action=hapusSatuan&pId="+id,
    cache: false,
    success: function(hasilHapusSatuan){
      alert("Hapus "+hasilHapusSatuan);
      $("#divTabelListSatuan").load("../src/gudang/proses_satuan_barang.php?action=listSatuan");
    }
  });
}
// end fungsi hapus

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
        <li class="" class="dropdown">
          <a href="#" class="dropdown-toggle active" data-toggle="dropdown"><span class="glyphicon glyphicon-folder-open"></span> &nbsp;Master <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="barang.php">Barang (Mentah)</a></li>
            <li><a href="supplier.php">Supplier</a></li>
            <li><a href="tahun_produksi.php">Tahun Produksi</a></li>
            <li><a href="#">Satuan Barang</a></li>
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
		<button id="idTambahSatuan" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Satuan Barang
		</button>
		<div id="divFormTambahSatuan">
			<div class="row">
				<div class="col-md-4"></div><!-- ruang kosong kiri -->
				<div class="col-md-4">
				<h3 align="center">Tambah Satuan Barang</h3>
        <div class="thumbnail">
          <div class="caption">
				<form id="formTambahSatuan">
					<label class="control-label" for="namasatuan">Nama Satuan</label>
        		<input id="idNamaSatuan" type="text" class="form-control" autocomplete="off" placeholder="Nama Satuan" required>
         		<br>
            <label class="control-label" for="nilaisatuan">Nilai</label>
            <input id="idNilaiSatuan" type="text" onkeyup="angka(this);" class="form-control" autocomplete="off" placeholder="Nilaiu Satuan" required>
            <br>
            <label class="control-label" for="satuankecil">Satuan Kecil</label>
            <input id="idSatuanKecil" type="text" class="form-control" autocomplete="off" placeholder="Satuan Nilai" required>
            <br>
            <label class="control-label" for="keteranga">Keterangan</label>
            <textarea id="idKeterangSatuan" type="text" class="form-control" autocomplete="off" placeholder="Keterangan Satuan"></textarea>
            <br>
            <div class="pull-right">
            	<button id="btnCancelSatuan" type="button" class="btn btn-default">
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
		<div id="divTabelListSatuan">

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
<div id="dialogEditSatuan">
  
</div>
</body>
</html>