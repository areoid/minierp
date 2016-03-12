<!DOCTYPE HTML>
<!-- halaman pengeluaran barang - produksi -->
<?php
include "../src/produksi/cek_session.php";
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Pengiriman Barang - Produksi</title>

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
				// load listSatuan
				$("#idJenisSatuan").load("../src/produksi/proses_pengiriman_barang.php?action=listSatuan");

				// load listPengiriman
				$("#divListPengirimanMen").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanMen");
				$("#divListPengirimanTer").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanTer");

				// hide warning
				$("#warning").hide();

				// val
				$("#idNoKirim").blur(function(){
					var nomer = $("#idNoKirim").val();
					$.ajax({
						url: "../src/produksi/proses_pengiriman_barang.php?action=cekNomer&nomer="+nomer,
						cache: false,
						success: function(hasilCek){
							if(hasilCek == 0){
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

				// belanja selesai
				$("#btnSelesai").click(function(){
					$("#formDetailPengiriman").hide("fade", { direction: "in" }, 1000);
					$("#divPengiriman").hide("fade", { direction: "in" }, 1000);
					$("#btnTambahPengiriman").show("fade", { direction: "out" }, 1000);
					$("#divListPengirimanMen").show("fade", { direction: "out" }, 1000);
					$("#divListPengirimanTer").show("fade", { direction: "out" }, 1000);
					$("#divListPengirimanMen").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanMen");
					$("#divListPengirimanTer").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanTer");
					$("#idNoKirim").val("");
					$("#idTanggal").val("");
					$("#idPengirim").val("");
					
					$.ajax({
						url: "../src/produksi/proses_pengiriman_barang.php?action=selesaiPengiriman",
						cache: false,
						success: function(hasilnya){
							setTimeout(function(){
						    	alert(hasilnya);
							}, 1000);
						}
					});
					

				});

				//  dibatalkan
				$("#btnCancelPengiriman").click(function(){
					var kdPengiriman = $("#kdPengiriman").html();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_pengiriman_barang.php?action=cancelPengiriman",
						data: {pKdPengiriman:kdPengiriman},
						cache: false,
						success: function(hasilCancelPengiriman){
							$("#formDetailPengiriman").hide("fade", { direction: "in" }, 1000);
							$("#divPengiriman").hide("fade", { direction: "in" }, 1000);
							$("#btnTambahPengiriman").show("fade", { direction: "out" }, 2000);
							$("#divListPengirimanMen").show("fade", { direction: "out" }, 2000);
							$("#divListPengirimanTer").show("fade", { direction: "out" }, 2000);
							$("#divListPengirimanMen").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanMen");
							$("#divListPengirimanTer").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanTer");
							$("#idNoKirim").val("");
							$("#idTanggal").val("");
							$("#idPengirim").val("");
						
						}
					});
				});

				// form detail belanja di submit
				$("#formDetailPengiriman").submit(function(){

					$("#btnSelesai").show("fade", { direction: "out" }, 2000);
					var vKodeBarang = $("#idKodeBarang").val();
					var vNamaBarang = $("#idNamaBarang").val();
					var vJumlahSatuan = $("#idJumlahSatuan").val();
					var vJenisSatuan = $("#idJenisSatuan").val();
					var vIsi = $("#idIsi").val();
					var vTotalSatuan = $("#idTotalSatuan").val();
					var vSatuanKecil = $("#idSatuanKecil").val();

					if(vTotalSatuan == "0"){
						alert("Tolong diisi dengan benar");
						return false;
					}
	
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_pengiriman_barang.php?action=tambahDetailPengiriman",
						data:{
							pKodeBarang:vKodeBarang,
							pNamaBarang:vNamaBarang,
							pJumlahSatuan:vJumlahSatuan,
							pJenisSatuan:vJenisSatuan,
							pIsi:vIsi,
							pTotalSatuan:vTotalSatuan,
							pJenisSatuanKecil:vSatuanKecil,
						},
						cache: false,
						success: function(hasilTambahDetailPengiriman){
							$("#divPengiriman").show("slide", { direction: "right" }, 1000);
							$("#divHeaderPengiriman").load("../src/produksi/proses_pengiriman_barang.php?action=headerPengiriman");
							$("#divDetailPengiriman").load("../src/produksi/proses_pengiriman_barang.php?action=listDetailPengiriman");

							// kosongkan form detail belanja
							$("#idKodeBarang").val("");
							$("#idNamaBarang").val("");
							$("#idJumlahSatuan").val("");
							$("#idIsi").val("");
							$("#idTotalSatuan").val("");
							//alert(hasilTambahDetailPengiriman);
							
						}
					});
				

					return false;
				});
				
				// hide form
				$("#formPengiriman").hide();

				//btn cancel tambah pengiriman diklik
				$("#btnCancelTambahPengiriman").click(function(){
					$("#idNoKirim").val("");
					$("#idTanggal").val("");
					$("#idPengirim").val("");
					$("#formPengiriman").slideUp(1000);
					$("#btnTambahPengiriman").show();
					$("#warning").hide();
				});

				// btn tambah pengiriman diklik
				$("#btnTambahPengiriman").click(function(){
					$("#btnTambahPengiriman").hide();
					$("#formPengiriman").slideDown(1500);
				});

				// auto hitung total satuan
				$("#idIsi").keyup(function(){
					var jumlahSatuan = $("#idJumlahSatuan").val();
					var Isi = $("#idIsi").val();
					var hasil = jumlahSatuan * Isi;
					$("#idTotalSatuan").val(hasil);
				});
				$("#idJumlahSatuan").keyup(function(){
					var jumlahSatuan = $("#idJumlahSatuan").val();
					var Isi = $("#idIsi").val();
					var hasil = jumlahSatuan * Isi;
					$("#idTotalSatuan").val(hasil);
				});

				// auto kode barang
				$("#idKodeBarang").autocomplete({
					source: "../src/produksi/proses_pengiriman_barang.php?action=autoKodeBarang",
					minLength:1,
					delay:0
				});

				// auto satuan kecil
				$("#idJenisSatuan").change(function(){
					var satuan = $("#idJenisSatuan").val();
					var jumlah = $("#idJumlahSatuan").val();

					$.ajax({
						url: "../src/produksi/proses_pengiriman_barang.php?action=autoSatuanKecil&sat="+satuan,
						cache: false,
						success: function(h){
							var hasilArr = h.split("*");
							$("#idIsi").val(hasilArr[0]);
							$("#idSatuanKecil").val(hasilArr[1]);
							var hasil = jumlah * hasilArr[0];
							$("#idTotalSatuan").val(hasil);
						}
					});
				});

				// auto nama barang
				$("#idKodeBarang").blur(function(){
					var krmKodeBarang = $("#idKodeBarang").val();
					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_pengiriman_barang.php?action=autoNamaBarang",
						data: {pKodeBarang:krmKodeBarang},
						cache: false,
						success: function(h){
							var hasilAutoNamaBarang = $.trim(h);
							if(hasilAutoNamaBarang == "gagal"){
								alert("Kode Barang Salah !!");
								$('button[name="simpan"]').attr('disabled','disabled');
								$("#idNamaBarang").val("Kode Barang Salah !!");
							}
							else{
								$("#idNamaBarang").val(hasilAutoNamaBarang);
								$('button[name="simpan"]').removeAttr('disabled');
							}
						}
					});
				});
				
				// hide divDetailBelanja
				$("#formDetailPengiriman").hide();

				//hide divBelanja
				$("#divPengiriman").hide();

				//hide btnSelesai
				$("#btnSelesai").hide();	

				// datepicker
				$("#idTanggal").datepicker({ dateFormat: 'yy-mm-dd' });

				$("#idProduksiPermintaan").click(function(){
					$.ajax({
						url: "../src/produksi/clear_session.php",
						cache: false,
						success: function(hasilLogout){
							alert(hasilLogout);
							window.location.reload();
						}
					});
				});

			    // form permintaan diklik
			    $("#formPengiriman").submit(function(){
					var vKode = $("#idNoKirim").val();
					var vTanggal = $("#idTanggal").val();
					var vAdmin = "<?php echo $_SESSION['username']; ?>";
					var vPengirim = $("#idPengirim").val();
					
					//alert(vKodePermintaan+vTanggalPermintaan+vAdmin);

					$.ajax({
						type: "POST",
						url: "../src/produksi/proses_pengiriman_barang.php?action=tambahPengiriman",
						data: {
								pKode:vKode,
								pTanggal:vTanggal,
								pAdmin:vAdmin,
								pPengirim:vPengirim
							},
						cache: false,
						success: function(hasilTambahPengiriman){
							//alert(hasilTambahPengiriman);
						}
					});
					$(this).hide("fade", { direction: "in" }, 1000);
					$("#divListPengirimanMen").hide("fade", { direction: "in" }, 1000);
					$("#divListPengirimanTer").hide("fade", { direction: "in" }, 1000);
					$("#formDetailPengiriman").show("slide", { direction: "right" }, 1000);
					$("#divPengiriman").show("slide", { direction: "right" }, 1000);
			    	return false;
			    });

			});
function angka(e) {
	if (!/^[0-9]+$/.test(e.value)) {
		e.value = e.value.substring(0,e.value.length-1);
   }
}

function lihatTerkonfirmasi(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_pengiriman_barang.php?action=lihatTerkonfirmasi",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatTerkonfirmasi){
			$("#dialogLihatBerita").html(hasilLihatTerkonfirmasi);
			$("#dialogLihatBerita").dialog({
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
		type: "POST",
		url: "../src/produksi/proses_pengiriman_barang.php?action=lihatPengiriman",
		data: {pKode:kode},
		cache: false,
		success: function(hasilLihatPengiriman){
			$("#dialogLihatBerita").html(hasilLihatPengiriman);
			$("#dialogLihatBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
	
}

function editPengiriman(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_pengiriman_barang.php?action=editPengiriman",
		data: {pKode:kode},
		cache: false,
		success: function(hasilEditPengiriman){
			$("#dialogEditBerita").html(hasilEditPengiriman);
			$("#dialogEditBerita").dialog({
				position:['middle',20],
				resizeable:true,
				width:1000,
				modal: true
			});
		}
	});
}

function hapusPengiriman(kode){
	$.ajax({
		type: "POST",
		url: "../src/produksi/proses_pengiriman_barang.php?action=hapusPengiriman",
		data: {pKode:kode},
		cache: false,
		success: function(hasilHapusPengiriman){
			alert(hasilHapusPengiriman);
			$("#divListPengirimanMen").load("../src/produksi/proses_pengiriman_barang.php?action=listPengirimanMen");
		}
	});
}

function printPengiriman(kode){
	var url = "../src/produksi/proses_pengiriman_barang.php?action=printPengiriman&pKode="+kode;
  	var win=window.open(url, '_blank');
  	win.focus();
  	//win.close();
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
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-book"></span> Transaksi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="permintaan_barang.php">Permintaan Barang Mentah</a></li>
            <li><a href="#">Pengiriman Barang Jadi</a></li>
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
		<li><a id="idProduksiPermintaan" href="#"><span class="glyphicon glyphicon-off"></span> Keluar</a></li>
	  </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navbar -->

<!-- kotak tengah -->
<div class="thumbnail">
	<div class="caption">
		<button id="btnTambahPengiriman" class="btn btn-primary">
			<span class="glyphicon glyphicon-plus"></span> Tambah Pengiriman
		</button>
		<br>
		<form id="formPengiriman">
			<h2 align="center">Form Pengiriman Barang</h2>
			<div class="row">
				<div class="col-md-4">
				</div><!-- ruang kosong kiri -->

				<div class="col-md-4">
					<div class="thumbnail">
						<div class="caption">
					<label class="control-label" for="kodePengiriman">No. Kirim</label>
					<input id="idNoKirim" type="text" class="form-control" autocomplete="off" placeholder="No. Kirim" required>
					<span id="warning" class="label label-danger pull-right"><span class="glyphicon glyphicon-warning-sign"></span> No. Kirim sudah ada</span>
					<br>
					<label class="control-label" for="tanggalPengiriman">Tanggal Pengiriman</label>
	             	<input id="idTanggal" type="text" class="form-control" autocomplete="off" placeholder="Tanggal Pengiriman" required>
	             	<br>
					<label class="control-label" for="pengirim">Pengirim</label>
	             	<input id="idPengirim" type="text" class="form-control" autocomplete="off" placeholder="Nama Pengirim" required>
					<br>
	             	<div class="pull-right">
		           		<button id="btnCancelTambahPengiriman" type="button" class="btn btn-default">
							<span class="glyphicon glyphicon-remove"></span> Cancel
						</button>
		          		<button type="submit" name="lanjut" class="btn btn-primary">
		           			LANJUT <span class="glyphicon glyphicon-chevron-right"></span>
		           		</button>
	              	</div>
	              	<br>
	              	<br>
	              		</div>
					</div>
				</div>

				<div class="col-md-4">
				</div><!-- ruang kosong kanan -->
			</div>
		</form>
		<br>
		<div id="divListPengirimanTer">

		</div>
		<br>
		<div id="divListPengirimanMen">

		</div>
		<form id="formDetailPengiriman">
			<h2 align="center">Item Perngiriman Barang</h2>
			<div class="thumbnail">
				<div class="caption">
			<table width="100%">
				<tr>
					<th>Kode Barang</th>
					<th>Nama Barang</th>
					<th>Jumlah</th>
					<th>Jenis Satuan</th>
					<th>Isi</th>
					<th>Total</th>
				</tr>
				<tr>
					<td width="10%"><input id="idKodeBarang" type="text" autocomplete="off" name="kodeBarang" class="form-control kodeBarang" placeholder="Kode Barang" required></td>
					<td><input id="idNamaBarang" type="text" autocomplete="off" class="form-control namaBarang" placeholder="Nama Barang" disabled></td>
					<td width="15%"><input id="idJumlahSatuan" autocomplete="off" type="text" name="jumlahSatuan" onkeyup="angka(this);" class="form-control jumlahSatuan" required></td>
					<td><select id ='idJenisSatuan' name='jenisSatuan' class='form-control'></select></td>
					<td width="10%"><input id="idIsi" type="text" autocomplete="off" name="Isi" onkeyup="angka(this);" class="form-control Isi" required></td>
					<td><input type="text" id="idTotalSatuan" class="form-control" disabled required></td>
					<td><input type="text" id="idSatuanKecil" class="form-control" disabled required></td>
				</tr>
			<table>
			<br>
			<div class="pull-right">
				<button type="submit" name="simpan" class="btn btn-primary">Simpan & Tambah Item</button>
				<button id="btnCancelPengiriman" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnSelesai" type="button" class="btn btn-primary">Selesai</button>
			</div>
			<br>
			<br>
				</div>
			</div>
		</form>
		<br>
		<div id="divPengiriman">
			<div id="divHeaderPengiriman">

			</div>
			<div id="divDetailPengiriman">

			</div>
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

		</div>
<div id="dialogLihatBerita">
	
</div>
<div id="dialogEditBerita">
	
</div>

	</body>
</html>